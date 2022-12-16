<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function listOrder(Request $request){   
        $orders = Order::with(['OrderProduct' => function($q){
            $q->with('products')->select('id','order_id','product_id');
        }])->get();
        return response()->json(['orders' => $orders]);
    }

    public function singleOrder($id){
        $orders = Order::with(['OrderProduct' => function($q){
            $q->with('products')->select('id','order_id','product_id');
        }])->where('id',$id)->first();
        return response()->json(['orders' => $orders]);
    }

}
