<?php

namespace App\Http\Controllers\Dashboard\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Client;
use App\Models\Order;
use App\Models\Company;
use App\Models\Admin;
use App\Product;

class OrderController extends Controller
{


    public function create(Admin $client)
    {
        $categories = Company::with('products')->get();
     return view('dashboard.clients.orders.create',compact('client','categories'));
    }

    public function store(Request $request,Admin $client)
    {

       $request->validate([
           'prdouct_ids'=>'required|array',
           'quantities'=>'required|array'
       ]);

      $this->attach_order($request,$client);
    //    $order = $client->orders()->create([]);
    //   $total_price = 0;
    //    foreach ($request->prdouct_ids as $index=>$product){
    //        $product = Product::FindOrFail($product);
    //    $total_price+= $product->sale_price * $request->quantities[$index];

    //     $order->products()->attach($product ,['quantity'=>$request->quantities[$index]]);
    //     $product->update(['stock'=>$product->stock - $request->quantities[$index]]);
    //    }
    //    $order->update(['total_price'=>$total_price]);
         session()->flash('success',__('site.added_successfully'));
        return redirect()->route('dashboard.orders.index');
    }


    public function edit(Admin $client,Order $order)
    {
        $categories = Company::all();
      return view('dashboard.clients.orders.edit',compact('client','order','categories'));

    }

    public function update(Request $request,Admin $client,Order $order)
    {
        $request->validate([
            'prdouct_ids'=>'required|array',
            'quantities'=>'required|array'
        ]);
       $this->detach_order($order);
       $this->attach_order($request,$client);

       session()->flash('success',__('site.updated_successfully'));
       return redirect()->route('dashboard.orders.index');

                 }




   private function attach_order(Request $request,Admin $client){

    $order = $client->orders()->create([]);
    $total_price = 0;
    foreach ($request->prdouct_ids as $index=>$product){
        $product = Product::FindOrFail($product);
    $total_price += $product->sale_price * $request->quantities[$index];

     $order->products()->attach($product ,['quantity'=>$request->quantities[$index]]);
     $product->update(['stock'=>$product->stock - $request->quantities[$index]]);
    }
    $order->update(['total_price'=>$total_price]);


   }
   private function detach_order(Order $order){

    foreach ($order->products as $product){

        $product->update([
      'stock'=>$product->stock + $product->pivot->quantity
        ]);

              }
              $order->delete();

   }

}
