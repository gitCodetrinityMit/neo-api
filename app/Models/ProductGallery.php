<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductGallery extends Model
{
    use HasFactory;
    protected $table = 'product_galleries';
    // protected $guarded = ['id'];
    protected $fillable = ['id','product_id','image'];

    /**
     * Product Gallery related with Product table for get Product Image wise get Product data.
     *
     * @return Relation
     * 
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'id', 'product_id');
    }


}
