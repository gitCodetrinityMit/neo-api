<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderProducts;

class OrdersController extends Controller
{
       
    public function createOrder(Request $request){
        
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
