<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductGallery;
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
    public function listProduct(Request $request) 
    {
  
        // Product Listing
        $product = Product::with('product_category.category')->select('id','name','slug','sku','selling_price','regular_price','description','stock','status','created_at')->orderBy('id','asc');

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

    /**
     * Product Field Data Add Into DataBase
     *
     * @return Message (error or success)
     * 
     */
    public function addProduct(Request $request)
    {
        // return $request->name;
        // Validation Check For Add Product
        $validator = Validator::make($request->all(),[
            // 'name'          =>      'required|string|min:5',
            // 'category_id'   =>      'required',
            // 'slug'          =>      'required|alpha_dash|unique:products'
            // 'images'        =>      'required|image|mimes:png,jpg,jpeg',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->messages()],401);
        }

        // Slug Create
        $slug = $request->slug;
        if (! $slug) {
            $slug = Str::slug(@$request->name);
        }

        // If Product Slug Already Existing Then Create Unique New Slug
        $productavailable = Product::where('slug', $slug)->count();
        if ($productavailable) {
            $lastproduct = Product::orderBy('id', 'desc')->first();
            $slug = $slug.'-'.($lastproduct->id + 1);
        }
        // dd(Product::first());
        // Product Store
        $product = new Product();
        $product->name = $request->name;
        $product->slug = $slug;
        $product->sku = $request->sku ?: Str::random(10);
        $product->selling_price = $request->selling_price;
        $product->regular_price = $request->regular_price;
        $product->description = $request->description;
        $product->stock = $request->stock;
        $product->status = $request->status;
        $product->save();

        // Product Category Add
        if($product->save()){
            $category_id = $request->category_id;
            $category_ids = explode(",", $category_id);
            foreach ($category_ids as $value) {
                $product_category = new ProductCategory();
                $product_category->product_id = $product->id;
                $product_category->category_id = $value;
                $product_category->save();
            }
        }
        
        // Product Images Store
        $image_name = '';
        $files = $request->images;
        if ($request->file('images')) {
            foreach ($files as $productimage) {
                $image_name = 'product/'.rand(100000, 999999).'.'.$productimage->getClientOriginalExtension();
                $productimage->move(public_path('storage/product/'), $image_name);

                // $images[] = $image_name;
                $image = new ProductGallery();
                $image->product_id = $product->id;
                $image->image = $image_name;
                $image->save();
            }
        }

        return response()->json(['product' => $product['name'] .' Product Created Success'],200);
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
        $product = Product::with('product_galleries','product_category')->where('id',$id)->first();
        if($product){
            return response()->json(['success' => $product],200);
        }else{
            return response()->json(['error' => 'Product Not Found'],401);
        }
    }

    
    /**
     * Update Product Detail In DataBase
     *
     * @param mixed $id
     * 
     * @return Message (error or success)
     * 
     */
    public function updateProduct(Request $request,$id)
    {
        $product = Product::where('id',$id)->first();

        // Validation Check For Update Product
        $validator = Validator::make($request->all(),[
            'name'          =>      'required|string|min:5',
            // 'category_id'   =>      'required',
            'slug'          =>      'required|alpha_dash|unique:products'
        //  'images'        =>      'required|image|mimes:png,jpg,jpeg',
        ]);

        if($validator->fails()){ 
            return response()->json(['error' => $validator->messages()],401);
        } 

        //Slug Create
        $slug = $request->slug;
        if (! $slug) {
            $slug = Str::slug(@$request->name);
        }
        $productavailble = Product::where('slug', $slug)->where('id', '!=', $request->id)->count();
        if ($productavailble) {
            $slug = $slug.'-'.($product->id);
        }

        $update_product = [
            'name'          =>      $request->name,
            'slug'          =>      $slug,
            'sku'           =>      $request->sku ?: Str::random(10),
            'selling_price' =>      $request->selling_price,
            'regular_price' =>      $request->regular_price,
            'description'   =>      $request->description,
            'stock'         =>      $request->stock,
            'status'        =>      $request->status,
        ];

        // Product Category Delete
        $category = $request->category;
        $deletecatIds = explode("," , $request->removeoldcategory);
        foreach($deletecatIds as $value){
            $categoryname = ProductCategory::where('id', $value)->first();
            if($categoryname){
                ProductCategory::where('id', $value)->delete();
            }
        }
        if($category != null){
            $category_id = $request->category;
            $category_ids = explode(",", $category_id);
            foreach ($category_ids as $value) {
                $product_category = new ProductCategory();
                $product_category->product_id = $product->id;
                $product_category->category_id = $value;
                $product_category->save();
            }
        }

        //Product Images Delete In Folder
        $files = $request->images;
        $deleteIds = explode(',', $request->removeoldimages);
        foreach ($deleteIds as $deleteId) {
            $productImg = ProductGallery::where('id', $deleteId)->first();
            if ($productImg) {
                $path = public_path()."/storage/$productImg->image";
                if (File::exists($path)) {
                    File::delete($path);
                }
                ProductGallery::where('id', $deleteId)->delete();
            }
        }

        if ($files != null) {
            //Product Image Update
            foreach ($files as $k => $productimage) {
                $image_name = 'product/'.rand(100000, 999999).'.'.$productimage->getClientOriginalExtension();
                $productimage->move(public_path('storage/product/'), $image_name);

                $image = new ProductGallery();
                $image->product_id = $request->id;
                $image->image = $image_name;
                $image->save();

                $product = Product::where('id', $request->id)->update($update_product);
            }
        }else {
            $product = Product::where('id', $request->id)->update($update_product);
        }

        return response()->json(['success' => $update_product['name'] . ' Updated Success'],200);
    }

    /**
     * Delete Product Detail 
     *
     * @return Message (error or success)
     * 
     */
    public function deleteProduct($id)
    {
        $product = Product::where('id', $id)->first();
        $product->delete();

        return response()->json(['success' => 'Product Deleted Successfully',],200);
    }
}
