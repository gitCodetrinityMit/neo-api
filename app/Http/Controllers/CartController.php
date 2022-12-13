<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function index()
    {
        if(Auth::check()){
            $user = auth()->user();
            $cart_list = Cart::with('products.product_galleries')->where('user_id','=',auth()->user()->id)->select('id','user_id','product_id','product_qty')->get();
            return response()->json(['success' => $cart_list, 'user' => $user],200);
        }else{
            return response()->json(['error' => 'Login To Continue!!!'],401);
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
        
            $product = Product::find($request->product_id);

            if($product){
                if($request->product_qty > $product->stock){
                    return response()->json(['error' => 'Product Quantity Invalid!!!'],401);
                }else{
                    $wishlist = Cart::where('user_id', auth()->user()->id)->where('product_id', $request->product_id)->exists();
                    if (empty($wishlist)) {
                        $wishlist = new Cart();
                        $wishlist->user_id = auth()->user()->id;
                        $wishlist->product_id = $request->product_id;
                        $wishlist->product_qty = $request->product_qty;
                        $wishlist->save();
                        return response()->json(['success' => 'Product Add To Cart'], 200);
                    }else{
                        return response()->json(['errro' => 'Already In Cart'], 401);
                    }
                }
            }
        }else{
            return response()->json(['error' =>  'You Are Not LoggedIn!!!'],401);
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
        }else{
            return response()->json(['error' => 'Login To Continue!!!'],401);
        }
    }

    public function updateCartItem(Request $request)
    {
        $product = Cart::with('products')->where('user_id',auth()->user()->id)->where('product_id',$request->product_id)->first();

       if($request->product_qty == 0){
          return response()->json(['error' => 'Selectd Product Quantity Not Allowed!!!'],401); 
       }

        if($product){
            if($request->product_qty > $product->products->stock){
                return response()->json(['error' => 'Product Stock Not Available!!!'],401);
            }else{
                $cart_update = [
                    'product_qty'    =>  $request->product_qty,
                    'total'          =>  $product->products->regular_price * $request->product_qty,
                    'subtotal'       =>  $product->products->regular_price * $request->product_qty
                ];
                Cart::where('product_id',$request->product_id)->where('user_id',auth()->user()->id)->update($cart_update);
                return response()->json(['success' => 'Cart Updated'],200);
            }
        }
    }
}
