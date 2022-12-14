<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function productList()
    {
        // Active Product Listing
        $active_products = Product::with('product_galleries')->where('status',1)->get();

        // Latest Product Lisitng
        $new_arrival_products = Product::with('product_galleries')->latest('id')->get();

        return response()->json([
            'active_product'        => $active_products,
            'new_arrival_products'  => $new_arrival_products,
        ],200);
    }
}
