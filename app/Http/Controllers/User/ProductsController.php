<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function productList(Request $request)
    {
        $product = Product::with('product_category.category')->select('id','name','slug','sku','selling_price','regular_price','description','short_description','stock','status','created_at')->orderBy('id','desc');

        $product = $product->with(['product_galleries' => function($q){
            $q->select('id','product_id','image');
        }]);
        // Product Price Filter
        if ($request->has('min_price') && $request->has('max_price')) {
            $min = $request->min_price;
            $max = $request->max_price;
            $product = $product->whereRaw('regular_price >= ?',$min)->whereRaw('regular_price <= ?',$max);
        }

        // Product Search
        if($request->search){
           $product = $product->where('name','like','%'.$request->search.'%');
        }

        // Product Category Search Multiple Category Id Wise        
        if ($request->category) {
            $arr = explode(",", $request->category);
            $product = $product->whereHas('product_category', function ($query) use ($arr) {
                $query->whereIn('category_id', $arr);
            });
        }

        $paginate = $request->show ? $request->show : 15;
        $product = $product->latest()->paginate($paginate);

        return response()->json(['products' => $product],200);
    }
}
