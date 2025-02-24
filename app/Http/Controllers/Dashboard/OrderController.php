<?php

namespace App\Http\Controllers\Dashboard;

use App\Enum\OrderStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Order;
use PDF;
use Numbers;
use Illuminate\Http\Request;
class OrderController extends Controller
{
    public function index(Request $request){

        $orders = Order::where(function ($query) use ($request) {
            if ($request->search) {
                $query->where('order_code', 'like', '%' . $request->search . '%')
                      ->orWhereHas('user', function ($q) use ($request) {
                          $q->where('first_name', 'like', '%' . $request->search . '%')
                            ->orWhere('last_name', 'like', '%' . $request->search . '%')
                          ;
                      });
            }
        });

        // Filter by order status
        if ($request->has('status') && $request->status != '') {
            $orders->where('status', $request->status);
        }

        if ($request->start_date && $request->end_date) {
            $startDate = $request->start_date . ' 00:00:00';
            $endDate = $request->end_date . ' 23:59:59';
            $orders->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($request->start_date) {
            $orders->whereDate('created_at', '>=', $request->start_date);
        } elseif ($request->end_date) {
            $orders->whereDate('created_at', '<=', $request->end_date);
        }
        $orders = $orders->latest()->paginate(10);

        return view('dashboard.orders.index',compact('orders'));
    }
    public function products($order_id){

    $order = Order::with(['orderDetails.product','orderCategories.category'])->find($order_id);
    return view('dashboard.orders._products',compact('order'));
    }
    public function destroy(Order $order){


        $order->delete();
   session()->flash('success',__('site.deleted_successfully'));
   return redirect()->route('dashboard.orders.index');


    }
    public function downloadInvoice($invoiceId)
    {
        // Find the invoice from the database
        $invoice = Order::with(['user', 'orderDetails.product','orderDetails.product','orderCategories.category'])->findOrFail($invoiceId);
        $currency = app()->getLocale() === 'ar' ? 'دينار كويتي' : 'KWD';

        $total_with_arabic = Numbers::TafqeetMoney($invoice->total_price);
        $total_with_arabic = str_replace(['ريالا', 'ريال'], 'دينار كويتي', $total_with_arabic);
        $total_with_arabic = str_replace('هللة', 'فلس', $total_with_arabic);
        
        $invoice['total_with_arabic'] = $total_with_arabic;
        $invoice['currency'] = $currency;
        // Generate PDF from the Blade view
        $pdf = PDF::loadView('pdf.invoice', compact('invoice'));

        // Download the generated PDF
         return $pdf->download('invoice-' . $invoice->id . '.pdf');
    }

    public function confirmOrder($orderId){
        $order = Order::findOrFail($orderId);
        $order->status = OrderStatusEnum::CONFIRMED;
        $order->save();
        session()->flash('success',__('site.confirmed_successfully'));
        return redirect()->route('dashboard.orders.index');

    }

}
