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
     * Product Buy Now
     *
     * @return Message (error or success)
     * 
     */
    public function productBuyNow(Request $request)
    {
        if(Auth::check()){

            // $product = Cart::with('products.product_galleries')->where('user_id', auth()->user()->id)->where('product_id', $request->product_id)->first();

            // if($product){ 
            //     if($request->product_qty > $product->products->stock){
            //         return response()->json(['error' => 'Product Quantity Invalid!!!'],401);
            //     }else{   
            //         $cart = Cart::with('products')->where('user_id', auth()->user()->id)->where('product_id', $request->product_id)->first();
            //         $product_qty_check = $cart->product_qty + ($request->product_qty);
            //         if($product_qty_check > $cart->products->stock){
            //             return response()->json(['error' => 'Product Quantity Error!!!'],401);
            //         }else{
            //             $cart_update = [
            //                 'product_qty'    =>  $product->product_qty + ($request->product_qty),
            //                 'subtotal'       =>  $product->products->regular_price * ($product->product_qty + $request->product_qty),
            //             ];
            //             Cart::where('product_id',$request->product_id)->where('user_id',auth()->user()->id)->update($cart_update);
                        
            //             // Product SubTotal  Value Get
            //             $cart_check = Cart::where('user_id', auth()->user()->id)->select(DB::raw('sum(subtotal) as subtotal_data'))->get();
            //             $total = $cart_check[0]->subtotal_data; 
                        
            //             // Update Total
            //             $cart_check = Cart::where('user_id', auth()->user()->id)->update(['total' => $total]);
            //             return response()->json(['success' => 'Product Cart Quantity Update'],200);
            //         }
            //     }
            // }else{
            //     $cart = Cart::where('user_id', auth()->user()->id)->where('product_id', $request->product_id)->exists(); 
            //     $product = Product::find($request->product_id);

            //     if($request->product_qty == 0){
            //         return response()->json(['error' => 'Selected Product Quantity Not Allowed!!!'],401); 
            //     }else if($request->product_qty > $product->stock){
            //         return response()->json(['error' => 'Product Quantity Invalid!!!'],401);
            //     }else{
            //         if (!$cart) { 
            //             $cart = new Cart();
            //             $cart->user_id = auth()->user()->id;
            //             $cart->product_id = $request->product_id;
            //             $cart->product_qty = $request->product_qty;
            //             $cart->subtotal = $product->regular_price * $request->product_qty;
            //             $cart_check = Cart::where('user_id', auth()->user()->id)->select(DB::raw('sum(subtotal) as subtotal_data'))->get();
            //             $total = $product->regular_price * $request->product_qty + $cart_check[0]->subtotal_data; 
            //             $cart->total = $total;
            //             Cart::where('user_id', auth()->user()->id)->update(['total' => $total]);
            //             $cart->save();
            //             return response()->json(['success' => 'Buy Now Product Add To Cart'], 200);
            //         }
            //     }
            // }

           // Buy Now Order Create
            $products = json_decode($request->product_data);
            if(!$products){
                return response()->json(['error' => 'Product Data Error!!!'],401);
            }else{
                $order = new Order;
                $order->user_id = auth()->user()->id;
                $order->shipping_price = $request->shipping_price;
                $order->payment_status = $request->payment_status;
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
                
                foreach ($products as $thisProduct) {
    
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

                    $cart = new BuyNow();
                    $cart->product_id = $thisProduct->id;
                    $cart->user_id = Auth::user()->id;
                    $cart->product_qty = $thisProduct->qty;
                    $cart->subtotal = $product_list->selling_price;
                    $cart->total = ($thisProduct->qty * $product_list->selling_price);
                    $cart->save();

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
                    BuyNow::where('user_id',auth()->user()->id)->delete(); 
                }

                $order_id = Order::with('orderProduct.products.product_galleries')->where('id',$order->id)->select('id','payment_status','order_status','shippping_address','order_number','payment_method')->first();
                
                Order::where('id',$order->id)->update(['order_number' => '#10000'.$order->id]);
                return response()->json(['success' => 'Order Created Successfully','order_id' => $order->id,
                'orderUserDetail' => $order_id],200);     
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
                        $cart_check = BuyNow::where('user_id', auth()->user()->id)->update(['total' => $total]);
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
            dd($product_check);
            
            if(!empty($product_check)){
                $product_check->delete();
                return response()->json(['success' => 'Product Cancel From Buy Now'],200);
            }else{
                return response()->json(['error' => 'Something Wrong!!!'],401);
            }
        }
    }
}
