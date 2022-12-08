<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use Illuminate\Support\Facades\File;
use App\Models\Wishlist;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $primarykey = 'id';

    public static function boot() {
        parent::boot();

        self::deleting(function($products) {
            $products->product_galleries()->each(function($images) {
                $imagepath = public_path()."/storage/$images->image";
                $result = File::exists($imagepath);
                if($result)
                {
                    File::delete($imagepath);
                }

                $images->delete();
            });

            $products->product_category()->each(function($detail){
                $detail->delete();
            });
        });
    }

    /**
     * Product related with Category table for relation Between Product and Category table Fields.
     *
     * @return Relation
     * 
     */
    public function category(){
        return $this->belongsTo(Category::class);
    }

    /**
     * Product related with product-gallery table for relation Between Product and Product Gallery Fields.
     *
     * @return Relation
     * 
     */
    public function product_galleries(){
        return $this->hasMany(ProductGallery::class,'product_id','id');
    }

    public function product_category()
    {
        return $this->hasMany(ProductCategory::class,'product_id','id');
    }

    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function cart()
    {
        return $this->hasMany(Cart::class);
    }
}
