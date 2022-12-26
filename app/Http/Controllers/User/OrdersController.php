<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderProducts;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Payment;
use Illuminate\Support\Facades\Validator;

class OrdersController extends Controller
{
       
    public function createOrder(Request $request){

        if(Auth::check()){
            
            $products = json_decode($request->product_data);
            if(!$products){
                return response()->json(['error' => 'Product Data Error!!!'],401);
            }else{
                $order = new Order;
                $order->user_id = auth()->user()->id;
                $order->shipping_price = $request->shipping_price;
                $order->payment_status = $request->payment_status;
                // $order->order_status = $request->order_status;
                $order->payment_method = $request->payment_method;
                $order->total_price = $request->total_price;
                $order->shippping_address = $request->shippping_address;
                $order->country = $request->country;
                $order->state = $request->state;
                $order->city = $request->city;
                $order->first_name = $request->first_name;
                $order->last_name = $request->last_name;
                $order->contact_no = $request->contact_no;
                $order->save();
                
                foreach ($products as $key => $thisProduct) {
    
                    // $cart_item = Cart::with('products')->where('user_id',auth()->user()->id)->select('id','user_id','product_id','product_qty','subtotal','total')->where('product_id',$thisProduct->id)->first();
    
                    $product_list = Product::where('id',$thisProduct->id)->first();
                    $product_total_price = ($thisProduct->qty) * ($thisProduct->qty * $product_list->selling_price);

                    $order_pro = new OrderProducts;
                    $order_pro->order_id = $order->id;
                    $order_pro->product_id = $thisProduct->id;
                    $order_pro->product_quantity = $thisProduct->qty;
                    $order_pro->product_name = $product_list->name;
                    $order_pro->product_slug = $product_list->slug;
                    $order_pro->unit_price = $thisProduct->qty * $product_list->selling_price;
                    $order_pro->product_total_price = $product_total_price;
                    $order_pro->save();

                    $payment = new Payment();
                    $payment->payment_method = $request->payment_method;
                    $payment->user_id = auth()->user()->id;
                    $payment->order_id = $order->id;
                    $payment->product_id = $thisProduct->id;
                    $payment->amount = $thisProduct->qty * $product_list->selling_price;
                    $payment->payment_amount = $product_total_price;
                    $payment->payer_email = auth()->user()->email;
                    $payment->payment_status = $request->payment_status;
                    $payment->save();
                }
                $cod_check = $request->cash_on_delivery_check;
                if($cod_check == 'COD'){
                    Cart::where('user_id',auth()->user()->id)->delete(); 
                }

                $order_id = Order::with('orderProduct.products.product_galleries')->where('id',$order->id)->select('id','payment_status','order_status','shippping_address','order_number','payment_method')->first();
                
                Order::where('id',$order->id)->update(['order_number' => '#10000'.$order->id]);
                return response()->json(['success' => 'Order Created Successfully','order_id' => $order->id,
                'orderUserDetail' => $order_id],200);     
            }
        }
    }

    public function orderList(Request $request){

        if(Auth::check()){
            $orders = Order::with('OrderProduct.products.product_galleries')->select('id','shipping_price','payment_status','order_status','payment_method','total_price','shippping_address','city','state','contact_no','country','user_id','total_price','created_at','updated_at','order_number')->where('user_id',auth()->user()->id)->orderBy('id','DESC');

            $paginate = $request->show ? $request->show : 10;
            $orders = $orders->latest()->paginate($paginate);
            
            return response()->json(['orders' => $orders],200);
        }
    }

    public function singleOrderShow(Request $request,$id){

        if(Auth::check()){
            $orders = Order::with('OrderProduct.products.product_galleries','payment')->select('id','first_name','last_name','shipping_price','payment_status','order_status','payment_method','total_price','shippping_address','city','state','contact_no','country','total_price','created_at','updated_at','order_number')->where('user_id',auth()->user()->id)->where('id',$id)->orderBy('id','DESC');

            $paginate = $request->show ? $request->show : 10;
            $orders = $orders->paginate($paginate);

            return response()->json(['orders' => $orders]);
        }
    }

    /**
     * Update Order Status
     *
     * @param mixed $id
     * 
     * @return Message (error or success)
     * 
     */
    public function cancelledOrder(Request $request,$id) 
    {
        $order_id = Order::where('id',$id)->select('payment_status','order_status')->first();
        $order_status = $request->order_status;
        if(!$order_id){
            return response()->json(['error' => 'Order Id Error!!!'],401);
        }else{
            if($order_status != 0){
                Order::where('id',$id)->update(['order_status' => 0]);
                return response()->json(['orderStatus' => 'Order Cancelled!!!'],200);
            }
        }
    }

    public function updateStatus(Request $request,$id) 
    {
        $order_id = Order::with('orderProduct.products.product_galleries')->where('id',$id)->select('id','payment_status','order_status','shippping_address','order_number','payment_method')->first();
        
        if(!$order_id){
            return response()->json(['success' => 'Order Id Error!!!'],401);
        }else{
            $order_status = $request->order_status;
            $payment_status = $request->payment_status;
            $transaction_id = $request->transaction_id;
            
            if($order_status == 1 && $payment_status == 1){
                
                $orders = OrderProducts::with('products.product_galleries')->where('order_id',$id)
                ->select('id','order_id','product_id','product_name','product_quantity')
                ->get();

                foreach ($orders as $order) {
                    // Stock Manage After Order Stage Successfully Completed
                    Product::where('id',$order->product_id)->decrement('stock',$order->product_quantity);
                }
                // Cart Clear After Order Processing Completed
                Cart::where('user_id',auth()->user()->id)->delete();
            }
 
            Order::where('id',$id)->update(['order_status' => $order_status,'payment_status' => $payment_status,]);
            Payment::where('order_id',$id)->update(['payment_status'  => $payment_status,'transaction_id' => $transaction_id]);
            return response()->json(['orderStatus' => 'Order Updated','orderDetail'  => $order_id],200);
        }
    }
}
