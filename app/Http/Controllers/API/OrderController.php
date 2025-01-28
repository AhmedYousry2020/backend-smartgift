<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Http\Resources\OrderResource;
use App\Http\Traits\HttpResponsesTrait;
use App\Models\Category;
use App\Models\Mosque;
use App\Models\Order;
use App\Models\OrderCategory;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use HttpResponsesTrait;

    public function __construct(){
        $this->middleware('auth:api');
    }

    public function createOrder(Request $request)
    {
        $validated = $request->validate([
            'order_type' => 'required|string|in:custom,high_need',
            'category_id' => 'required_if:order_type,high_need|exists:categories,id',

            // Validation for "custom" order type
            'mosques' => 'required_if:order_type,custom|array',
            'mosques.*.id' => 'required_if:order_type,custom|exists:mosques,id',
            'mosques.*.products' => 'required_if:order_type,custom|array',
            'mosques.*.products.*.id' => 'required|exists:products,id',
            'mosques.*.products.*.quantity' => 'required|integer|min:1',

            // Validation for "high_need" order type
            'products' => 'required_if:order_type,high_need|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $order = Order::create([
            'order_type' => $validated['order_type'],
            'user_id'=>auth()->user()->id,
            'status' => 'pending',
            'total_price' => 0,  // will be updated later
        ]);

        $totalPrice = 0;

        if ($validated['order_type'] == 'custom') {
            foreach ($validated['mosques'] as $mosqueData) {
                $mosque = Mosque::find($mosqueData['id']);
                foreach ($mosqueData['products'] as $productData) {
                    $product = Product::find($productData['id']);
                    $totalPrice += $product->price * $productData['quantity'];

                    OrderDetail::create([
                        'order_id' => $order->id,
                        'mosque_id' => $mosque->id,
                        'product_id' => $product->id,
                        'quantity' => $productData['quantity'],
                        'price' => $product->price,
                        'total_price' => $product->price * $productData['quantity'],
                    ]);
                }
            }
        } elseif ($validated['order_type'] == 'high_need') {
            // Get the category from the validated category ID
            $category = Category::find($validated['category_id']);

            // Create the relationship between the order and category
            OrderCategory::create([
                'order_id' => $order->id,
                'category_id' => $category->id,
            ]);

            // Loop through the products in the validated request
            foreach ($validated['products'] as $productData) {
                // Retrieve the product by ID from the request
                $product = Product::find($productData['id']);

                // Check if the product exists (optional, for validation purposes)
                if ($product) {
                    // Calculate total price based on quantity from the request
                    $totalPrice += $product->price * $productData['quantity'];

                    // Create the order detail entry with the requested quantity
                    OrderDetail::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $productData['quantity'],
                        'price' => $product->price,
                        'total_price' => $product->price * $productData['quantity'],
                    ]);
                }
            }
        }
        $order->total_price = $totalPrice;
        $order->save();
        return $this->success(message: __('Order Created Successfully'), data: new OrderResource($order), status: 200);

    }



    public function viewOrder($orderId)
    {
        $order = Order::with(['orderDetails.product', 'orderDetails.mosque', 'orderCategories.category'])->find($orderId);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Return the formatted order response using the OrderResource
         return $this->success(message: __('Order Details'), data: new OrderResource($order), status: 200);
    }


    public function getMyOrders()
    {
        $orders = Order::where('user_id', auth()->id())->get();

           // Return the formatted order response using the OrderResource
           return  OrderResource::collection($orders);
    }

}
