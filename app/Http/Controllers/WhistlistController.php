<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductGallery;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class WhistlistController extends Controller
{
    /**
     * Product Wishlist Data
     *
     * @return JOSN $json
     * 
     */
    public function index() {
        if(Auth::check()){
            $user = auth()->user();
            $wishlist = Wishlist::with('products.product_galleries')->where('user_id','=',auth()->user()->id)->select('id','user_id','product_id')->get();

            return response()->json(['success' => $wishlist,'user' =>  $user],200); 
        }else{
            return response()->json(['error' => 'Login First!!!'],401);
        }
    }

    /**
     * Product Add In Wishlist
     *
     * @return Message (error or success)
     * 
     */
    public function addWishlistProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 403);
        }
        
        if(Auth::check()){
            
            $wishlist = Wishlist::where('user_id', auth()->user()->id)->where('product_id', $request->product_id)->exists();

            if (empty($wishlist)) {
                $wishlist = new Wishlist();
                $wishlist->user_id = auth()->user()->id;
                $wishlist->product_id = $request->product_id;
                $wishlist->save();
                return response()->json(['success' => 'Product Add To Wishlist'], 200);
            }else{
                return response()->json(['errro' => 'Already In Wishlist'], 401);
            }
        }else{
            return response()->json(['error' =>  'You Are Not LoggedIn!!!'],401);
        }
    }

    /**
     * Remove Product From Wishlist
     *
     * @param Request $request
     * 
     * @return Message (error or success)
     * 
     */
    public function removeWishlistProduct(Request $request)
    {
        if(Auth::check()){
            $product = Wishlist::with('products','product_galleries')
            ->where('user_id',auth()->user()->id)->where('product_id',$request->product_id)->first();
            if($product){
                $product->delete(); 
                return response()->json(['success' => 'Product Remove From Wishlist'],200);
            }else{
                return response()->json(['error' => 'Product Id Error!!!'],401);
            }
        }else{
            return response()->json(['error' => 'Login To Continue!!!'],401);
        }
        
    }
}
