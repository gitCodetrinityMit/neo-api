<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WhistlistController extends Controller
{
    public function index() {
        // if(Auth::check()){
            // $user = Auth::user();
            $wishlist = Wishlist::where('user_id',1)->latest('id')->get();   
            return response()->json(['success' => $wishlist],200);
        // }
    }
}
