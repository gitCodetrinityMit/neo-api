<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index(Request $request)
    {
        if(Auth::check()){
            $user = auth()->user();
            $cart_list = Cart::with('products.product_galleries')->where('user_id','=',auth()->user()->id)->select('id','user_id','product_id','product_qty','total','subtotal')->get();
            return response()->json(['success' => $cart_list, 'user' => $user],200);
        }
    }

    /**
     * Add Product In Cart
     *
     * @return Message (error or success)
     * 
     */
    public function addProductCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 403);
        }
        
        if(Auth::check()){
        
            $product = Cart::with('products')->where('user_id', auth()->user()->id)->where('product_id', $request->product_id)->first();

            if($product){ 
                
                if($request->product_qty > $product->products->stock){
                    return response()->json(['error' => 'Product Quantity Invalid!!!'],401);
                }else{   
                    $cart = Cart::with('products')->where('user_id', auth()->user()->id)->where('product_id', $request->product_id)->first();
                    $product_qty_check = $cart->product_qty + ($request->product_qty);
                    if($product_qty_check > $cart->products->stock){
                        return response()->json(['error' => 'Product Quantity Error!!!'],401);
                    }else{
                        $cart_update = [
                            'product_qty'    =>  $product->product_qty + ($request->product_qty),
                            'subtotal'       =>  $product->products->regular_price * ($product->product_qty + $request->product_qty),
                        ];
                        Cart::where('product_id',$request->product_id)->where('user_id',auth()->user()->id)->update($cart_update);
                        // Product SubTotal  Value Get
                        $cart_check = Cart::where('user_id', auth()->user()->id)->select(DB::raw('sum(subtotal) as subtotal_data'))->get();
                        $total = $cart_check[0]->subtotal_data; 
                        // Update Total In DataBase
                        $cart_check = Cart::where('user_id', auth()->user()->id)->update(['total' => $total]);
                        return response()->json(['success' => 'Product Cart Quantity Update'],200);
                    }
                }
            }else{
                $cart = Cart::where('user_id', auth()->user()->id)->where('product_id', $request->product_id)->exists(); 
                $product = Product::find($request->product_id);

                if($request->product_qty == 0){
                    return response()->json(['error' => 'Selected Product Quantity Not Allowed!!!'],401); 
                }else if($request->product_qty > $product->stock){
                    return response()->json(['error' => 'Product Quantity Invalid!!!'],401);
                }else{
                    if (!$cart) { 
                        $cart = new Cart();
                        $cart->user_id = auth()->user()->id;
                        $cart->product_id = $request->product_id;
                        $cart->product_qty = $request->product_qty;
                        $cart->subtotal = $product->regular_price * $request->product_qty;
                        $cart_check = Cart::where('user_id', auth()->user()->id)->select(DB::raw('sum(subtotal) as subtotal_data'))->get();
                        $total = $product->regular_price * $request->product_qty + $cart_check[0]->subtotal_data; 
                        $cart->total = $total;
                        Cart::where('user_id', auth()->user()->id)->update(['total' => $total]);
                        $cart->save();
                        return response()->json(['success' => 'Product Add To Cart'], 200);
                    }
                }
            }
        }
    }

    /**
     * Remove Product From Cart
     *
     * @param Request $request
     * 
     * @return Message (error or success)
     * 
     */
    public function removeCartProduct(Request $request)
    {
        if(Auth::check()){
            $product = Cart::with('products','product_galleries')
            ->where('user_id',auth()->user()->id)->where('product_id',$request->product_id)->first();
            if($product){
                $product->delete(); 
                return response()->json(['success' => 'Product Remove From Cart'],200);
            }else{
                return response()->json(['error' => 'Product Id Error!!!'],401);
            }
        }
    }

    public function updateCartItem(Request $request)
    {
        $product = Cart::with('products')->where('user_id',auth()->user()->id)->where('product_id',$request->product_id)->first();

       if($request->product_qty == 0){
        $cart_list = Cart::with('products.product_galleries')->where('user_id','=',auth()->user()->id)->select('id','user_id','product_id','product_qty')->get();
          return response()->json(['error' => 'Selectd Product Quantity Not Allowed!!!','cart' =>  $cart_list],401); 
       }

        if($product){
            if($request->product_qty > $product->products->stock){
                $cart_list = Cart::with('products.product_galleries')->where('user_id','=',auth()->user()->id)->select('id','user_id','product_id','product_qty')->get();
                return response()->json(['error' => 'Product Stock Not Available!!!', 'cart' =>  $cart_list],401);
            }else{
                $cart_update = [
                    'product_qty'    =>  $request->product_qty,
                    'subtotal'       =>  $product->products->regular_price * $request->product_qty,
                    // 'total'          =>  $product->products->regular_price * $request->product_qty,
                ];
                Cart::where('product_id',$request->product_id)->where('user_id',auth()->user()->id)->update($cart_update);
                
                // Product SubTotal  Value Get
                $cart_check = Cart::where('user_id', auth()->user()->id)->select(DB::raw('sum(subtotal) as subtotal_data'))->get();
                $total = $cart_check[0]->subtotal_data; 
                // Update Total In DataBase
                
                if($request->flat_rate == 50){
                    $total = $cart_check[0]->subtotal_data + 50;
                }else{
                    $total = $cart_check[0]->subtotal_data;
                }
                
                Cart::where('user_id', auth()->user()->id)->update(['total' => $total]);

                $cart_list = Cart::with('products.product_galleries')->where('user_id','=',auth()->user()->id)->select('id','user_id','product_id','product_qty')->get();
                
                return response()->json(['success' => 'Cart Updated', 'cart' =>  $cart_list],200);
            }
        }
    }

    /**
     * Update Flat Amount In Total
     *
     * @return Message (error or success)
     * 
     */
    public function updateFlatRate(Request $request)
    {
        // add this function changed
        $cart_check = Cart::where('user_id', auth()->user()->id)->select(DB::raw('sum(subtotal) as subtotal_data'))->get();
        $total = $cart_check[0]->subtotal_data; 
        
        if($request->flat_rate == 50){
            $total = $cart_check[0]->subtotal_data + 50;
        }else{
            $total = $cart_check[0]->subtotal_data;
        }
        Cart::where('user_id', auth()->user()->id)->update(['total' => $total]);
        return response()->json(['success' => 'Discount Addedd'],200);
    }
}
