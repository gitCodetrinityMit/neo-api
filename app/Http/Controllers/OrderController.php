<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;

class OrderController extends Controller
{
    public function listOrder(Request $request){ 
        $orders = Order::with('OrderProduct.products.product_galleries')->select('id','shipping_price','payment_status','order_status','user_id','payment_method','shippping_address','total_price','created_at','updated_at')->selectRaw('DATE_FORMAT(created_at,"%d, %b %Y / %h:%i %p") as date')->orderBy('id','DESC');

        $orders = $orders->with(['user' => function($q){
            $q->select('id','email','user_name','first_name','last_name');
        }]);

        $paginate = $request->show ? $request->show : 10;
        $orders = $orders->latest()->paginate($paginate);
        
        return response()->json(['orders' => $orders]);
    }

    public function singleOrder(Request $request,$id){
        $orders = Order::with('OrderProduct.products.product_galleries')->select('id','user_id','shipping_price','payment_status','order_status','payment_method','shippping_address','total_price','created_at','updated_at')->selectRaw('DATE_FORMAT(created_at,"%d, %b %Y / %h:%i %p") as date')->where('id',$id)->orderBy('id','DESC');

        $orders = $orders->with(['user' => function($q){
            $q->select('id','email','user_name','first_name','last_name');
        }]);

        $paginate = $request->show ? $request->show : 10;
        $orders = $orders->paginate($paginate);

        return response()->json(['orders' => $orders]);
    }

    /**
     * Update Order Status
     *
     * @param mixed $id
     * 
     * @return Message (error or success)
     * 
     */
    public function updateOrderStatus(Request $request,$id) 
    {
        $order_id = Order::where('id',$id)->select('payment_status','order_status')->first();

        if(!$order_id){
            return response()->json(['success' => 'Order Id Error!!!'],401);
        }else{
            $order_status = $request->order_status;
            $payment_status = $request->payment_status;
            Order::where('id',$id)->update(['order_status' => $order_status,'payment_status'  => $payment_status]);
            // Order::where('id',$id)->update(['payment_status'  => $payment_status]);
            Payment::where('order_id',$id)->update(['payment_status'  => $payment_status]);
            return response()->json(['orderStatus' => 'Order Status Updated'],200);
        }
    }

}
