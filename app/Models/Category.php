<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Category extends Model
{
    use HasFactory;
    protected $table = 'categories';
    protected $primarykey = 'id';


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
}
