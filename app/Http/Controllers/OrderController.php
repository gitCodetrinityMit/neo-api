<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function listOrder(Request $request){   
        $orders = Order::with('OrderProduct.products.product_galleries')->select('id','shipping_price','payment_status','order_status','user_id','payment_method','shippping_address','total_price','created_at','updated_at')->orderBy('id','DESC');

        $orders = $orders->with(['user' => function($q){
            $q->select('id','email','user_name','first_name','last_name');
        }]);

        $paginate = $request->show ? $request->show : 10;
        $orders = $orders->latest()->paginate($paginate);
        
        return response()->json(['orders' => $orders]);
    }

    public function singleOrder(Request $request,$id){
        $orders = Order::with('OrderProduct.products')->select('id','user_id','shipping_price','payment_status','order_status','payment_method','shippping_address','total_price','created_at','updated_at')->where('id',$id)->orderBy('id','DESC');

        $orders = $orders->with(['user' => function($q){
            $q->select('id','email','user_name','first_name','last_name');
        }]);

        $paginate = $request->show ? $request->show : 10;
        $orders = $orders->paginate($paginate);

        return response()->json(['orders' => $orders]);
    }

}
