<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderProducts;
use Illuminate\Support\Facades\Auth;

class OrdersController extends Controller
{
       
    public function createOrder(Request $request){
        if(Auth::check()){

            $product = $request->product_id;
            $product = explode(',',$product);

            $order = new Order;
            $order->user_id = auth()->user()->id;
            $order->price = $request->price;
            $order->quantity = $request->quantity;
            $order->shipping_price = $request->shipping_price;
            $order->payment_status = $request->payment_status;
            $order->order_status = $request->order_status;
            $order->payment_method = $request->payment_method;
            $order->total_price = $request->total_price;
            $order->shippping_address = $request->shippping_address;
            $order->save();
            
            foreach ($product as $key => $pro) {
                $order_pro = new OrderProducts;
                $order_pro->order_id = $order->id;
                $order_pro->product_id = $pro;
                $order_pro->save();
            }
            return response()->json(['success' => 'Order Created Successfully.'],200);
        }

    }

    public function orderList(Request $request){

        if(Auth::check()){
            $orders = Order::select('id','price','quantity','shipping_price','payment_status','order_status','payment_method','total_price','shippping_address')->orderBy('id','DESC');

            $orders = $orders->with(['OrderProduct' => function($q){
                $q->with(['products' => function($q){
                    $q->select('id','name','slug','sku','selling_price','regular_price','description','short_description','stock','status');
                }])->select('id','order_id','product_id');
            }]);

            $paginate = $request->show ? $request->show : 10;
            $orders = $orders->latest()->paginate($paginate);
            
            return response()->json(['orders' => $orders],200);
        }
    }

    public function singleOrderShow(Request $request,$id){

        if(Auth::check()){
            $orders = Order::select('id','price','quantity','shipping_price','payment_status','order_status','payment_method','total_price','shippping_address')->where('id',$id)->orderBy('id','DESC');

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
}
