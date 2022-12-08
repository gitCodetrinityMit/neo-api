<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    public function products() {
        return $this->belongsTo(Product::class,'product_id','id');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function product_galleries(){
        return $this->belongsTo(ProductGallery::class,'product_id','id');
    }
}
