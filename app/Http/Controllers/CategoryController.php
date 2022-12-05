<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Category Field List
     *
     * @return JSON $json
     * 
     */
    public function listCategory()
    {
        // Get All Parent Top Level Category
        $parentCategories = Category::select('id','parent_id', 'slug','name','status','created_at')
        ->where('parent_id', 0)->get();

        // Get Nestable Data
        $categories = Category::nestable($parentCategories);

        return response()->json(['category' =>  $categories],200);
    }

    /**
     * Add Category Field Data In DataBase
     *
     * @param Request $request
     * 
     * @return Message (error or success)
     * 
     */
    public function addCategory(Request $request)
    {
        // Validation Check For Add Category
        $validator = Validator::make($request->all(),[
            'name'      =>      'required|string',
            'slug'      =>      'required|alpha_dash|unique:categories'
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->messages()],401);
        }

        // Slug Create
        $slug = $request->slug;
        if (!$slug) {
            $slug = Str::slug(@$request->name);
        }

        $categoryavailable = Category::where('slug', $slug)->count();
        if ($categoryavailable) {
            $lastcategory = Category::orderBy('id', 'desc')->first();
            $slug = $slug.'-'.($lastcategory->id + 1);
        }

        // Category Store
        $category = new Category();
        $category->name = $request->name;
        $category->slug = $slug;
        $category->parent_id = $request->parent_id;
        $category->status = $request->status;
        $category->save();

        return response()->json(['success' => $category['name'] . ' Category Created Success'],200);
    }

    /**
     * [Description for editCategory]
     *
     * @param mixed $id
     * 
     * @return [type]
     * 
     */
    public function editCategory($id)
    {
        $category = Category::with(['parent' => function($query){
            $query->select('id','slug','name');
        }])
        ->select('id','name','slug','parent_id','status')
        ->where('id',$id)
        ->first();

        if($category){
            return response()->json(['success' => $category],200);
        }else{
            return response()->json(['error' => 'Category Not Found'],401);
        }
    }

    public function updateCategory(Request $request,$id)
    {
        $category = Category::where('id', $id)->first();
                
        // Slug Create
        $slug = $request->slug;
        if(!$slug){
            $slug = Str::slug(@$request->name);
        }
        $categoryavailable = Category::where('slug', $slug)->where('id', '!=', $request->id)->count();
        if($categoryavailable){
            $slug = $slug.'-'. Str::random(3);
        }

        // Update Category
        $update_category = [
            'name'      =>      $request->name,
            'slug'      =>      $slug,
            'parent_id' =>      $request->parent_id,
            'status'    =>      $request->status
        ];

        Category::where('id',$id)->update($update_category);

        return response()->json(['success' => $update_category["name"] .' Category Updated Success'],200);
    }

    /**
     * Delete Category Field Detail
     *
     * @param mixed $id
     * 
     * @return Message (error or success)
     * 
     */
    public function deleteCategory($id)
    {
        $category = Category::where('id',$id)->first();
        
        if($category){
            Product::with('product_category')->where('id',$id)->delete();
            $category->delete();
            return response()->json(['success' => 'Category Deleted Success'],200);
        }else{
            return response()->json(['error' => 'Category Delete Error'],401);
        }
    }
}
