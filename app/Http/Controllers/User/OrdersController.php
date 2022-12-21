<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderProducts;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Cart;

class OrdersController extends Controller
{
       
    public function createOrder(Request $request){
         /*$product = $request->product_id;
         $product = explode(',',$product);*/
        if(Auth::check()){
            $products = json_decode($request->product_data);
            if(!$products){
                return response()->json(['error' => 'Product Data Error!!!'],401);
            }else{
                $order = new Order;
                $order->user_id = auth()->user()->id;
                $order->shipping_price = $request->shipping_price;
                $order->payment_status = $request->payment_status;
                $order->order_status = $request->order_status;
                $order->payment_method = $request->payment_method;
                $order->total_price = $request->total_price;
                $order->shippping_address = $request->shippping_address;
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
                }
                Cart::where('user_id',auth()->user()->id)->delete();
                return response()->json(['success' => 'Order Created Successfully','order_id' => $order->id],200);     
            }
        }
    }

    public function orderList(Request $request){

        if(Auth::check()){
            $orders = Order::with('OrderProduct.products')->select('id','shipping_price','payment_status','order_status','payment_method','total_price','shippping_address')->orderBy('id','DESC');

            // $orders = $orders->with(['OrderProduct' => function($q){
            //     $q->with(['products' => function($q){
            //         $q->select('id','name','slug','sku','selling_price','regular_price','description','short_description','stock','status');
            //     }])->select('id','order_id','product_id');
            // }]);

            $paginate = $request->show ? $request->show : 10;
            $orders = $orders->latest()->paginate($paginate);
            
            return response()->json(['orders' => $orders],200);
        }
    }

    public function singleOrderShow(Request $request,$id){

        if(Auth::check()){
            $orders = Order::with('OrderProduct.products')->select('id','shipping_price','payment_status','order_status','payment_method','total_price','shippping_address')->where('id',$id)->orderBy('id','DESC');

            // $orders = $orders->with(['OrderProduct' => function($q){
            //     $q->with(['products' => function($q){
            //         $q->select('id','name','slug','sku','selling_price','regular_price','description','short_description','stock','status');
            //     }])->select('id','order_id','product_id');
            // }]);

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
    public function updateOrderStatus($id) 
    {
        
    }
}
