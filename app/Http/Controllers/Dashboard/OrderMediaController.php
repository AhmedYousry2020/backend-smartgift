<?php

namespace App\Http\Controllers\Dashboard;

use App\Enum\OrderStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderMedia;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderMediaController extends Controller
{

    public function index($orderId)
    {
        $orderMedia = OrderMedia::where('order_id',$orderId)->get();
        $order = Order::find($orderId);
        return view('dashboard.orders.media.index',compact('orderMedia','order'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //dd($request->all());
        $request->validate([
            'media' => 'required|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi|max:20480', // 20MB limit
            'order_id' => 'required|exists:orders,id'
        ]);

        $request_data = $request->only(['order_id']);
        $order = Order::find($request_data['order_id']);

        if ($request->hasFile('media')) {
            $media = $request->file('media');
            $filename = time() . '.' . $media->getClientOriginalExtension();

            // Determine if it's an image or video
            $folder = in_array($media->getClientOriginalExtension(), ['jpg', 'jpeg', 'PNG', 'gif']) ? 'images' : 'videos';

            // Move the file to the correct directory
            $media->move(public_path("uploads/order_media/{$folder}/"), $filename);

            // Save the path in the database
            $request_data['media_path'] = "uploads/order_media/{$folder}/" . $filename;
            $request_data['type'] = $folder === 'images' ? 'image' : 'video';
        }
        OrderMedia::create($request_data);
        if($order->status == OrderStatusEnum::CONFIRMED)
        {
            $order->status = OrderStatusEnum::COMPLETE;
            $order->save();
        }
        session()->flash('success', __('site.added_successfully'));

        return redirect()->route('dashboard.order.media.index', ['orderId' => $request->order_id]);
    }
    public function destroy($id)
    {
        $media = OrderMedia::findOrFail($id);

        // Delete file from public storage
        if (file_exists(public_path($media->media_path))) {
            unlink(public_path($media->media_path));
        }

        // Delete record
        $media->delete();

        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->back();
    }

}
