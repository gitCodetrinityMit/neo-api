<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    /**
     * Product List
     *
     * @return JSON $json
     * 
     */
    public function listProduct() 
    {
        // Product Listing
        $products = Product::latest('id')->get();

        return response()->json(['products' => $products],200);
    }

    /**
     * Product Field Data Add Into DataBase
     *
     * @return Message (error or success)
     * 
     */
    public function addProduct(Request $request)
    {
        // Validation Check For Add Product
        $validator = Validator::make($request->all(),[
            'name'          =>      'required|string|min:5',
            'category_id'   =>      'required',
            // 'images'        =>      'required|image|mimes:png,jpg,jpeg',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->messages()],401);
        }

        // Generate Unique Slug Validation
        $rules = [];
        $rules['slug'] = 'unique:products';

        // Slug Create
        $slug = $request->slug;
        if (! $slug) {
            $slug = Str::slug(@$request->name);
        }

        $productavailable = Product::where('slug', $slug)->count();
        if ($productavailable) {
            $lastproduct = Product::orderBy('id', 'desc')->first();
            $slug = $slug.'-'.($lastproduct->id + 1);
        }

        // Product Image
        $image_name = '';
        $images = [];
        $files = $request->images;
        if ($request->file('images')) {
            foreach ($files as $productimage) {
                $image_name = 'product/'.rand(100000, 999999).'.'.$productimage->getClientOriginalExtension();
                $productimage->move(public_path('storage/product/'), $image_name);
                $images[] = $image_name;
            }
        }

        // Product Store
        $product = new Product();
        $product->name = $request->name;
        $product->slug = $slug;
        $product->sku = $request->sku ?: Str::random(10);
        $product->category_id = $request->category_id;
        $product->selling_price = $request->selling_price;
        $product->regular_price = $request->regular_price;
        $product->images = implode(",",$images);
        $product->description = $request->description;
        $product->stock = $request->stock;
        $product->status = $request->status;
        $product->save();

        return response()->json(['product' => $product['name'] .'Product Created Success'],200);
    }

    /**
     * Product Detail(id) Wise For Update
     *
     * @param mixed $id
     * 
     * @return JSON
     * 
     */
    public function editProduct($id)
    {
        $product = Product::where('id',$id)->first();
        if($product){
            return response()->json(['success' => $product],200);
        }else{
            return response()->json(['error' => 'Product Not Found'],401);
        }
    }

    /**
     * Delete Product Detail 
     *
     * @return Message (error or success)
     * 
     */
    public function deleteProduct($id)
    {
        $product = Product::where('id',$id)->first();

        if($product){
            $images = explode(",", $product->images);
            foreach($images as $image){

                $image_path = public_path()."/storage/$image";
         
                if(File::exists($image_path)) {
                    File::delete($image_path);
                }
            }

            Product::where('id', $id)->delete();
            return response()->json(['success' => 'Product Deleted Success'],200);
        }else{
            return response()->json(['error' => 'Product Deleted Error'],401);
        }
    }
}
