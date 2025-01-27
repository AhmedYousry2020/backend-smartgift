<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Http\Traits\HttpResponsesTrait;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Mosque;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class CartOrderController extends Controller
{
    use HttpResponsesTrait;

    public function __construct(){
        $this->middleware('auth:api');
    }

    public function addToCart(Request $request)
    {

        // Validate input
        $request->validate([
            'high_need' => 'required|boolean', // Whether it's a high-need order
            'mosque_items' => 'required|array', // List of mosques with their products and quantities
            'mosque_items.*.mosque_id' => 'required_if:high_need,false|exists:mosques,id', // Mosque ID for custom cart
            'mosque_items.*.items' => 'required|array', // List of products for each mosque
            'mosque_items.*.items.*.product_id' => 'required|exists:products,id', // Product ID
            'mosque_items.*.items.*.quantity' => 'required|integer|min:1', // Product quantity
        ]);
        // Get the authenticated user's cart
        $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);

        if ($request->high_need) {
            // Option 2: High-Need Mosques - Add products for all high-need mosques
            $highNeedMosques = Mosque::where('is_high_need', true)->get();

            if ($highNeedMosques->isEmpty()) {
                return response()->json(['message' => 'No high-need mosques found'], 404);
            }

            foreach ($highNeedMosques as $mosque) {
                // Add products to high-need mosques (without specifying mosque_id)
                foreach ($request->mosque_items as $mosqueItem) {
                    CartItem::updateOrCreate(
                        [
                            'cart_id' => $cart->id,
                            'product_id' => $mosqueItem['product_id'],
                            'mosque_id' => $mosque->id,
                        ],
                        ['quantity' => $mosqueItem['quantity']]
                    );
                }
            }

        } else {
            // Option 1: Custom Cart - Add products for specific mosques
            foreach ($request->mosque_items as $mosqueItem) {
                foreach ($mosqueItem['items'] as $item) {
                    // Add product to the cart for the specific mosque
                    CartItem::updateOrCreate(
                        [
                            'cart_id' => $cart->id,
                            'product_id' => $item['product_id'],
                            'mosque_id' => $mosqueItem['mosque_id'],
                        ],
                        ['quantity' => $item['quantity']]
                    );
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Products added to cart',
            'status' => 200
        ]);
    }




    public function removeFromCart($itemId)
    {
        $cartItem = CartItem::find($itemId);

        if (!$cartItem) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        $cartItem->delete();
        return $this->success(message: __('Item removed from cart'), status: 200);
    }

    public function deleteCart()
    {
        $cart = Cart::where('user_id', auth()->id())->first();

        if (!$cart) {
            return response()->json(['message' => 'Cart not found'], 404);
        }

        $cart->items()->delete();
        $cart->delete();
        return $this->success(message: __('Cart deleted successfully'), status: 200);

    }

    public function updateCartItems(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:cart_items,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        foreach ($request->items as $itemData) {
            $cartItem = CartItem::find($itemData['id']);

            if ($cartItem) {
                $cartItem->update(['quantity' => $itemData['quantity']]);
            }
        }

        return response()->json(['message' => 'Cart items updated successfully']);
    }


    public function viewCart(Request $request)
    {
        // Get the authenticated user's cart
        $cart = Cart::where('user_id', auth()->id())->first();

        if (!$cart) {
            return response()->json(['message' => 'Cart is empty'], 404);
        }

        // Fetch cart items and group them by mosque
        $cartItems = CartItem::where('cart_id', $cart->id)
                            ->with(['product', 'mosque']) // Include product and mosque details
                            ->get();

        // Group cart items by mosque
        $groupedByMosque = $cartItems->groupBy(function ($item) {
            return $item->mosque ? $item->mosque->name : 'High Need'; // Group by mosque or high need
        });

        $response = [];
        $totalPrice = 0;

        // Process each mosque or high need group
        foreach ($groupedByMosque as $mosqueName => $items) {
            $mosqueTotal = 0;
            $mosqueItems = [];

            foreach ($items as $item) {
                $itemTotal = $item->quantity * $item->product->price;
                $mosqueTotal += $itemTotal;

                $mosqueItems[] = [
                    'product_name' => $item->product->name,
                    'quantity' => $item->quantity,
                    'total_price' => $itemTotal
                ];
            }

            // Add mosque data to the response
            $response[] = [
                'mosque_name' => $mosqueName,
                'items' => $mosqueItems,
                'total_price' => $mosqueTotal
            ];

            $totalPrice += $mosqueTotal;
        }

        // Return response with the cart items and total price
        return response()->json([
            'success' => true,
            'message' => 'Cart details fetched successfully',
            'data' => [
                'cart' => $response,
                'total_price' => $totalPrice
            ]
        ]);
    }


    public function createOrder(Request $request)
    {
        $request->validate([
            'type' => 'required|in:custom,high_need', // Define order type: custom or high_need
            'mosques' => 'required|array', // Validate mosques is an array
            'mosques.*.mosque_id' => 'required|exists:mosques,id', // Ensure mosque IDs exist
            'mosques.*.products' => 'required|array', // Validate each mosque has products
            'mosques.*.products.*.product_id' => 'required|exists:products,id', // Ensure product exists
            'mosques.*.products.*.quantity' => 'required|integer|min:1', // Ensure valid quantity
            'mosques.*.products.*.price' => 'required|numeric|min:0', // Ensure valid price for each item
            'total_price' => 'required|numeric|min:0', // Total price for the order
        ]);

        if ($request->type === 'custom') {
            // **Custom Order Creation**
            return $this->createCustomOrder($request->mosques, $request->total_price);
        } elseif ($request->type === 'high_need') {
            // **High-Need Order Creation**
            return $this->createHighNeedOrder($request->mosques, $request->total_price);
        }

        return response()->json(['message' => 'Invalid order type'], 400);
    }



    public function getOrderDetails($id)
    {
        $order = Order::with(['mosques', 'items.product'])->find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        return $this->success(message: __('Order Details'), data: $order, status: 200);

    }

     /**
     * Create a custom order.
     */
    private function createCustomOrder(array $mosqueIds, array $items, $totalPrice)
    {
        $order = Order::create([
            'user_id' => auth()->id(),
            'status' => 'pending',
            'type' => 'custom',
            'total_price' => $totalPrice, // Store the total price sent in the request

        ]);

        foreach ($mosqueIds as $mosqueId) {
            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'mosque_id' => $mosqueId,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'total_price' => $item['price']
                ]);
            }
        }

        return response()->json(['message' => 'Custom order created successfully', 'order' => $order], 201);
    }

    /**
     * Create an order for high-need mosques.
     */
    private function createHighNeedOrder(array $items, $totalPrice)
    {
        $highNeedMosques = Mosque::where('is_high_need', true)->get();

        if ($highNeedMosques->isEmpty()) {
            return response()->json(['message' => 'No high-need mosques found'], 404);
        }

        $order = Order::create([
            'user_id' => auth()->id(),
            'status' => 'pending',
            'type' => 'high_need',
            'total_price' => $totalPrice, // Store the total price sent in the request

        ]);
            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'mosque_id'=>null,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'total_price' =>$item['price']
                ]);
        }

        return response()->json(['message' => 'High-need order created successfully', 'order' => $order], 201);
    }

}
