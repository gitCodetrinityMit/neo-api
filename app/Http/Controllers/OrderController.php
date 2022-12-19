<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function listOrder(Request $request){   
        $orders = Order::select('id','shipping_price','payment_status','order_status','payment_method','total_price','shippping_address')->orderBy('id','DESC');

        $orders = $orders->with(['OrderProduct' => function($q){
            $q->with(['products' => function($q){
                $q->select('id','name','slug','sku','selling_price','regular_price','description','short_description','stock','status');
            }])->select('id','order_id','product_id');
        }]);

        $paginate = $request->show ? $request->show : 10;
        $orders = $orders->latest()->paginate($paginate);
        
        return response()->json(['orders' => $orders]);
    }

    public function singleOrder(Request $request,$id){
        $orders = Order::select('id','shipping_price','payment_status','order_status','payment_method','total_price','shippping_address')->where('id',$id)->orderBy('id','DESC');

        $orders = $orders->with(['OrderProduct' => function($q){
            $q->with(['products' => function($q){
                $q->select('id','name','slug','sku','selling_price','regular_price','description','short_description','stock','status');
            }])->select('id','order_id','product_id');
        }]);

        $paginate = $request->show ? $request->show : 10;
        $orders = $orders->paginate($paginate);

        return response()->json(['orders' => $orders]);
    }

}
