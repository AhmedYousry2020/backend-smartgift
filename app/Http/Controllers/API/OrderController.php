<?php

namespace App\Http\Controllers\API;

use App\Enum\OrderStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Http\Resources\OrderMediaResource;
use App\Http\Resources\OrderResource;
use App\Http\Traits\HttpResponsesTrait;
use App\Models\Category;
use App\Models\Mosque;
use App\Models\Order;
use App\Models\OrderCategory;
use App\Models\OrderDetail;
use App\Models\OrderMedia;
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
            'order_for' => 'required|string|in:men,women,both',
            'category_id' => 'required_if:order_type,high_need|exists:categories,id',
            'note'=>'nullable',
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

        // Get the last order code and increment it
        $lastOrder = Order::orderBy('id', 'desc')->first();
        $nextOrderCode = '#1'; // default to #1 if no orders exist yet

        if ($lastOrder) {
            // Get the last order code and increment the number part
            $lastOrderNumber = (int)substr($lastOrder->order_code, 1);
            $nextOrderCode = '#' . ($lastOrderNumber + 1);
        }
        $order = Order::create([
            'order_type' => $validated['order_type'],
            'order_for' => $validated['order_for'],
            'user_id'=>auth()->user()->id,
            'note'=>$validated['note'] ?? null,
            'status' => OrderStatusEnum::NOT_COMPLETE,
            'total_price' => 0,  // will be updated later
            'order_code' => $nextOrderCode, // Store the generated order code
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
        $orders = Order::where('user_id', auth()->id())->latest()->get();

           // Return the formatted order response using the OrderResource
           return $this->success(message: __('My Orders Details'), data: OrderResource::collection($orders), status: 200);
    }

    public function lastActivities($limit = 3)
    {
        $lastOrders = Order::with(['user', 'orderDetails'])->latest()->take($limit)->get();

        if ($lastOrders->isEmpty()) {
            return response()->json(['message' => 'No orders found'], 404);
        }
        return $this->success(message: __('Last Activity'),
         data: $lastOrders->map(function ($order) {
            $total_quantity = $order->orderDetails->sum('quantity');
            return [
                'id' => $order->id,
                'user' => [
                    'id' => $order->user->id,
                    'first_name' => $order->user->first_name  ,
                    'last_name'  => $order->user->last_name
                ],
                'order_type' => $order->order_type,
                'total_quantity'=>$total_quantity,
                'status' => $order->status,
                'total_price' => $order->total_price,
                'created_at' => $order->created_at->toDateTimeString(),
            ];
        }),
         status: 200);


    }

    public function getMedia($orderId)
    {
        $orderMedia = OrderMedia::where('order_id',$orderId)->get();
        return $this->success(message: __('My Order Media'), data: OrderMediaResource::collection($orderMedia), status: 200);
    }

    public function remakeOrder($orderId)
    {
        // Find the old order by ID
        $oldOrder = Order::with(['orderDetails.product', 'orderCategories.category'])->find($orderId);

        if (!$oldOrder) {
            return response()->json(['message' => 'Order not found'], 404);
        }
        $lastOrder = Order::orderBy('id', 'desc')->first();
        $nextOrderCode = '#1'; // default to #1 if no orders exist yet

        if ($lastOrder) {
            $lastOrderNumber = (int)substr($lastOrder->order_code, 1);
            $nextOrderCode = '#' . ($lastOrderNumber + 1);
        }
        // Start by creating a new order using the same user_id as the old one
        $newOrder = Order::create([
            'order_type' => $oldOrder->order_type,
            'order_for' => $oldOrder->order_for,
            'user_id' => $oldOrder->user_id,
            'status' => OrderStatusEnum::NOT_COMPLETE,  // Default status can be 'pending'
            'total_price' => 0,  // Will calculate the price after creating order details
            'order_code' => $nextOrderCode,  // You can reuse the code generation logic
        ]);

        $totalPrice = 0;

        // Remake the order details from the old order
        foreach ($oldOrder->orderDetails as $orderDetail) {
            // Remake the product orders (recreate them for the new order)
            OrderDetail::create([
                'order_id' => $newOrder->id,
                'product_id' => $orderDetail->product_id,
                'mosque_id' => $orderDetail->mosque_id, // If it's a mosque-based order
                'quantity' => $orderDetail->quantity,
                'price' => $orderDetail->price,
                'total_price' => $orderDetail->total_price,
            ]);

            // Update the total price
            $totalPrice += $orderDetail->total_price;
        }

        // If the old order had categories (for high need orders), remake the order categories
        foreach ($oldOrder->orderCategories as $orderCategory) {
            OrderCategory::create([
                'order_id' => $newOrder->id,
                'category_id' => $orderCategory->category_id,
            ]);
        }

        // Update the total price of the new order
        $newOrder->total_price = $totalPrice;
        $newOrder->save();

        // Return the response with the newly created order
        return $this->success(message: __('Order Remade Successfully'), data: new OrderResource($newOrder), status: 200);
    }
    public function pay($orderId){
        $order = Order::findOrFail($orderId);
        $order->status = OrderStatusEnum::PENDING;
        $order->save();
        return $this->success(message: __('Order Remade Successfully'), data: new OrderResource($order), status: 200);

    }


}
