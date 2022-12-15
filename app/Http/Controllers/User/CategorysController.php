<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategorysController extends Controller
{
    public function categoryList()
    {
        // Active Category Display
        $category = Category::where('status',1)->orderBy('id','asc')->where('parent_id', 0)->get();
        $categories = Category::nestable($category);
        return response()->json(['category' => $categories],200);
    }
}
