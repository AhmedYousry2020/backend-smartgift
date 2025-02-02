<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
 public function index(){

$categories = Category::all();
$products = Product::all();
$clients = User::all();
$users = Admin::all();
$sales_data = Order::select(
    DB::raw('YEAR(created_at) as year'),
    DB::raw('MONTH(created_at) as month'),
    DB::raw('SUM(total_price) as sum')
)
->groupBy('year', 'month') // Add 'year' in the GROUP BY clause
->get();
   return view('dashboard.index',compact('categories','products','users','clients','sales_data'));

 }
}
