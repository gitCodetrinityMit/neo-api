<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $primarykey = 'id';


    /**
     * Product related with Category table for relation Between Product and Category table Fields.
     *
     * @return Relation
     * 
     */
    public function category(){
        return $this->belongsTo(Category::class);
    }
}
