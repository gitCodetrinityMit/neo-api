<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Category extends Model
{
    use HasFactory;
    protected $table = 'categories';
    // protected $primarykey = 'id';

    // Define Eloquent Parent Child Relationship
    public function parent() {
        return $this->belongsTo(self::class, 'parent_id');
    }

    // For First Level Child
    public function children() {
        return $this->hasMany(self::class, 'parent_id')->select('id','parent_id', 'slug','name','status');
    }

    // For Nestable Child.
    public static function nestable($categories) {
        foreach ($categories as $category) {
            if (!$category->children->isEmpty()) {
                $category->children = self::nestable($category->children);
            }
        }
        return $categories;
    }

    public static function boot() {
        parent::boot();

        self::deleting(function($category) {
            $category->children()->each(function($children) {
                $children->delete();
            });
        });
    }

    /**
    * Category related with Product table for Relation Beetween Category and Product.
    *
    * @return Relation
    *
    */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function product_category()
    {
        return $this->hasMany(ProductCategory::class,'category_id','id');
    }
}
