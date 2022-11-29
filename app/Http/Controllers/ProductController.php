<?php

namespace App\Http\Controllers;

use App\Models\Product;
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
        $products = Product::with('product_galleries','category')->select('id','name','slug','sku','category_id','selling_price','regular_price','description','stock','status')->orderBy('id','asc');

        // Product Search With Multiple Category Wise
        $category = $request->category;
        $category_ids = explode(",", $category);

        if($request->category){
            $products = $products->whereIn('category_id',$category_ids);
        }

        // Product Search Name Wise
        if($request->search){
            $products = $products->where('name', 'like', '%' .$request->search .'%'); 
        }

        // Product Filter
        if ($request->has('min_price') && $request->has('max_price')) {
            $min = $request->input('min_price');
            $max = $request->input('max_price');
            $products = $products->newQuery()
	            ->whereRaw('regular_price >= ?',$min)
                ->whereRaw('regular_price <= ?',$max);
        }else{
            $products = Product::with('product_galleries','category')->select('id','name','slug','sku','category_id','selling_price','regular_price','description','stock','status')->orderBy('id','asc');
        }

        $paginate = $request->show ? $request->show : 10;
        $products = $products->latest()->paginate($paginate);

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
            'slug'          =>      'required|alpha_dash|unique:products'
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

        // Product Store
        $product = new Product();
        $product->name = $request->name;
        $product->slug = $slug;
        $product->sku = $request->sku ?: Str::random(10);
        $product->category_id = $request->category_id;
        $product->selling_price = $request->selling_price;
        $product->regular_price = $request->regular_price;
        // $product->images = implode(",",$images);
        $product->description = $request->description;
        $product->stock = $request->stock;
        $product->status = $request->status;
        $product->save();

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
        $product = Product::where('id',$id)->first();
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
            'category_id'   =>      'required',
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
            'category_id'   =>      $request->category_id,
            'selling_price' =>      $request->selling_price,
            'regular_price' =>      $request->regular_price,
            'description'   =>      $request->description,
            'stock'         =>      $request->stock,
            'status'        =>      $request->status,
        ];

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
