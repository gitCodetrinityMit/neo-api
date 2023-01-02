<?php

namespace App\Http\Controllers;

use App\Models\BuyNow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Models\OrderProducts;
use App\Models\Payment;

class BuyNowController extends Controller
{

    /**
     * Product Buy Now List
     *
     * @return JSON $json
     * 
     */
    public function buyNowList()
    {
        if(Auth::check()){
            $buynow_product = BuyNow::with('products.product_galleries')->where('user_id',auth()->user()->id)->get();
            return response()->json(['success' => $buynow_product],200);
        }
    }

    /**
     * Product Buy Now
     *
     * @return Message (error or success)
     * 
     */
    public function productBuyNow(Request $request)
    {
        if(Auth::check()){

            // Check Already Product Existing Or Not
            $product_check = BuyNow::where('product_id',$request->product_id)->first();
            if($product_check){
               return response()->json(['error' => 'Product Buy Now Error!!!'],401); 
            }else{
                // Create Buy Now Product Order
                $product_detail = Product::with('product_galleries')->where('id',$request->product_id)->first();
                $buy_now = new BuyNow();
                $buy_now->user_id = auth()->user()->id;
                $buy_now->product_id = $request->product_id;
                $buy_now->product_qty = $request->product_qty;
                $buy_now->subtotal = $product_detail->selling_price; 
                $buy_now->total = $product_detail->selling_price * $request->product_qty;
                $buy_now->save();

                $order = new Order();
                $order->user_id = auth()->user()->id;
                $order->shipping_price = $request->shipping_price ? $request->shipping_price : '1000';
                $order->payment_status = $request->payment_status ? $request->payment_status : 2;
                $order->order_status = $request->order_status ? $request->order_status : 2;
                $order->payment_method = $request->payment_method ? $request->payment_method : 'COD';
                $order->total_price = $product_detail->selling_price * $request->product_qty;
                $order->country = $request->country ? $request->country : 'India';
                $order->state = $request->state ? $request->state : 'Gujarat';
                $order->contact_no = $request->contact_no ? $request->contact_no : '0123456789';
                $order->city = $request->city ? $request->city : 'Surat';
                $order->first_name = $request->first_name ? $request->first_name : 'User Name';
                $order->last_name = $request->last_name ? $request->last_name : 'User Last Name';
                $order->shippping_address = $request->shippping_address ? $request->shippping_address : 'User Address';
                $order->save();

                $order_product = new OrderProducts();
                $order_product->order_id = $order->id;
                $order_product->product_id = $product_detail->id;
                $order_product->product_name = $product_detail->name;
                $order_product->product_slug = $product_detail->slug;
                $order_product->product_quantity = $request->product_qty;
                $order_product->unit_price = $product_detail->selling_price;
                $order_product->product_total_price = $request->product_qty * $product_detail->selling_price;
                $order_product->save();

                $order_payment = new Payment();
                $order_payment->payment_method = $request->payment_method ? $request->payment_method : 'COD';
                $order_payment->user_id = auth()->user()->id;
                $order_payment->order_id = $order->id;
                $order_payment->product_id = $product_detail->id;
                $order_payment->transaction_id = $request->transaction_id ? $request->transaction_id : '';
                $order_payment->amount = $request->product_qty * $product_detail->selling_price;
                $order_payment->payment_amount = ($request->product_qty) * ($request->product_qty * $product_detail->selling_price);
                $order_payment->payer_email = auth()->user()->email;
                $order_payment->payment_status = $request->payment_status ? $request->payment_status : 2;
                $order_payment->save();

                Order::where('id',$order->id)->update(['order_number' => '#10000'.$order->id]);
                return response()->json(['success' => 'Product Buy Now Success'],200);
            }
        }
    }

    /**
     * Update Product Detail In buy Now
     *
     * @param Request $request
     * 
     * @return Message (error or success)
     * 
     */
    public function updateProductBuyNow(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 403);
        }
        
        if(Auth::check()){
        
            $product = BuyNow::with('products')->where('user_id', auth()->user()->id)->where('product_id', $request->product_id)->first();

            if($product){ 
                if($request->product_qty > $product->products->stock){
                    return response()->json(['error' => 'Product Quantity Invalid!!!'],401);
                }else{   
                    $cart = BuyNow::with('products')->where('user_id', auth()->user()->id)->where('product_id', $request->product_id)->first();
                    $product_qty_check = $cart->product_qty + ($request->product_qty);
                    if($product_qty_check > $cart->products->stock){
                        return response()->json(['error' => 'Product Quantity Error!!!'],401);
                    }else{
                        $cart_update = [
                            'product_qty'    =>  $product->product_qty + ($request->product_qty),
                            'subtotal'       =>  $cart->subtotal,
                        ];
                        BuyNow::where('product_id',$request->product_id)->where('user_id',auth()->user()->id)->update($cart_update);
                        
                        // Product SubTotal  Value Get
                        $cart_check = BuyNow::where('user_id', auth()->user()->id)->select('product_qty','subtotal')->first();
                        
                        $total = $cart_check->subtotal * $cart_check->product_qty; 
                        // Update Total In DataBase
                        $cart_check = BuyNow::where(['user_id'=> auth()->user()->id, 'product_id' => $request->product_id])->update(['total' => $total]);
                        return response()->json(['success' => 'Product Cart Quantity Update'],200);
                    }
                }
            }else{
                return response()->json(['error' => 'Product Error'],401);
            }
        }
    }

    /**
     * Update Buy Now Product Payment Status
     *
     * @param Request $request
     * 
     * @return Message (error or success)
     * 
     */
    public function updateBuyNowStatus(Request $request,$id)
    {
        $order_id = Order::with('orderProduct.products.product_galleries')->where('id',$id)->select('id','payment_status','order_status','shippping_address','order_number','payment_method')->first();
        if(!$order_id){
            return response()->json(['success' => 'Order Id Error!!!'],401);
        }else{
            $order_status = $request->order_status;
            $payment_status = $request->payment_status;
            $transaction_id = $request->transaction_id;
            
            if($order_status == 1 && $payment_status == 1){      
                
                $orders = OrderProducts::with('products.product_galleries')->where('order_id',$id)->select('id','order_id','product_id','product_name','product_quantity')->first();

                // Stock Manage After Order Stage Successfully Completed
                Product::where('id',$orders->product_id)->decrement('stock',$orders->product_quantity);

                // BuyNow Clear After Order Processing Completed
                BuyNow::where('user_id',auth()->user()->id)->delete();
            }
 
            Order::where('id',$id)->update(['order_status' => $order_status,'payment_status' => $payment_status,]);
            Payment::where('order_id',$id)->update(['payment_status'  => $payment_status,'transaction_id' => $transaction_id]);
            return response()->json(['orderStatus' => 'Order Updated','orderDetail'  => $order_id],200);
        }
    }

    /**
     * Cancel Buy Now 
     *
     * @param Request $request
     * 
     * @return Message (errro or success)
     * 
     */
    public function cancelBuyNow(Request $request)
    {
        if(Auth::check()){
            $product_check = BuyNow::with('products.product_galleries')->where('user_id',auth()->user()->id)->where('product_id',$request->product_id)->first();            
            if(!empty($product_check)){
                $product_check->delete();
                return response()->json(['success' => 'Product Cancel From Buy Now'],200);
            }else{
                return response()->json(['error' => 'Something Wrong!!!'],401);
            }
        }
    }
}
