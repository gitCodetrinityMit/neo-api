<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;

    public static function boot() {
        parent::boot();

        self::deleting(function($products) {
            $products->products()->each(function($detail){
                $detail->delete();
            });
        });
    }

    public function products()
    {
        return $this->belongsTo(Product::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
