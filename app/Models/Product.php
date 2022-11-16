<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use Illuminate\Support\Facades\File;

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
}
