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
           if($order_status == 2){
               $update_order_status = [
                   'order_status'  =>  2,
                   'updated_at'    =>  date('Y-m-d H:i:s')
               ];
           }else if($order_status == 1){
                $update_order_status = [
                    'order_status'  =>  1,
                    'updated_at'    =>  date('Y-m-d H:i:s')
                ];
           }else if($order_status == 0){
                $update_order_status = [
                    'order_status'  =>  0,
                    'updated_at'    =>  date('Y-m-d H:i:s')
                ];
           }else if($order_status == 3){
                $update_order_status = [
                    'order_status'  =>  3,
                    'updated_at'    =>  date('Y-m-d H:i:s')
                ];
        }
        Order::where('id',$id)->update($update_order_status);
        return response()->json(['orderStatus' => 'Order Status Updated'],200);
        }
    }

}
