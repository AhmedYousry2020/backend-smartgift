<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
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
        $request->validate([
            'mosque_id' => 'required|exists:mosques,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);

        $cartItem = CartItem::updateOrCreate(
            [
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
                'mosque_id' => $request->mosque_id,
            ],
            ['quantity' => $request->quantity]
        );
        return $this->success(message: __('Product added to cart'), data: $cartItem, status: 200);

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



    public function viewCart()
    {
        $cart = Cart::with(['items.product', 'items.mosque'])->where('user_id', auth()->id())->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 200);
        }

        return $this->success(message: __('Cart Details'), data: $cart,  status: 200);

    }


    public function createOrder(Request $request)
    {
        $request->validate([
            'type' => 'required|in:custom,high_need', // Define order type: custom or high_need
            'mosque_ids' => 'required_if:type,custom|array', // Required if custom order
            'mosque_ids.*' => 'exists:mosques,id', // Ensure mosques exist
            'items' => 'required_if:type,custom|array', // Products & quantities for custom orders
            'items.*.product_id' => 'exists:products,id',
            'items.*.quantity' => 'integer|min:1',
        ]);

        if ($request->type === 'custom') {
            // **Custom Order Creation**
            return $this->createCustomOrder($request->mosque_ids, $request->items);
        } elseif ($request->type === 'high_need') {
            // **High-Need Order Creation**
            return $this->createHighNeedOrder();
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
    private function createCustomOrder(array $mosqueIds, array $items)
    {
        $order = Order::create([
            'user_id' => auth()->id(),
            'status' => 'pending',
            'type' => 'custom',
        ]);

        foreach ($mosqueIds as $mosqueId) {
            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'mosque_id' => $mosqueId,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                ]);
            }
        }

        return response()->json(['message' => 'Custom order created successfully', 'order' => $order], 201);
    }

    /**
     * Create an order for high-need mosques.
     */
    private function createHighNeedOrder(array $items)
    {
        $highNeedMosques = Mosque::where('is_high_need', true)->get();

        if ($highNeedMosques->isEmpty()) {
            return response()->json(['message' => 'No high-need mosques found'], 404);
        }

        $order = Order::create([
            'user_id' => auth()->id(),
            'status' => 'pending',
            'type' => 'high_need',
        ]);
            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'mosque_id'=>null,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                ]);


        }

        return response()->json(['message' => 'High-need order created successfully', 'order' => $order], 201);
    }

}
