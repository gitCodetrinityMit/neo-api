<?php

namespace App\Http\Controllers;

use App\Models\OrderProducts;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class RecentSellingController extends Controller
{
    /**
     * RecentSelling Product List
     *
     * @return JSON $json
     * 
     */
    public function recentSellingProduct()
    {
        $recent_selling_product = Order::with('orderProduct.products.product_galleries')->where('payment_status',1)->where('order_status',1)    ->limit(5)->latest('id')->get();
        return response()->json(['recentSellingProduct' => $recent_selling_product],200);
    }

    /**
     * Today Product Order Listing
     *
     * @return JSON $json
     * 
     */
    public function todayOrderList()
    {
        $today_orders = Order::with(['orderProduct'=> function($query){
            $query->select('id','order_id','product_name','unit_price','product_total_price');
        }])
        ->select('id','order_number','user_id','order_status')
        ->whereDate('created_at', date('Y-m-d'));

        $today_orders = $today_orders->with(['user' => function($query) {
            $query->select('id','user_name');
        }])->get();
        
        return response()->json(['todayOrderList' => $today_orders],200);
    }
}
