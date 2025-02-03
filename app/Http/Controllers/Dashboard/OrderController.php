<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
class OrderController extends Controller
{
    public function index(Request $request){

        $orders = Order::whereHas('user',function($q) use($request){

            return $q->where('first_name','like','%'.$request->search.'%');

        })->latest()->paginate(3);


        return view('dashboard.orders.index',compact('orders'));
    }
    public function products($order_id){

    $order = Order::with('orderDetails.product')->find($order_id);
    return view('dashboard.orders._products',compact('order'));
    }
    public function destroy(Order $order){


        $order->delete();
   session()->flash('success',__('site.deleted_successfully'));
   return redirect()->route('dashboard.orders.index');


    }
}
