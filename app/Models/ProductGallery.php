<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductGallery extends Model
{
    use HasFactory;
    protected $table = 'product_galleries';
    protected $primarykey = 'id';
    protected $fillable = ['product_id'];

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
