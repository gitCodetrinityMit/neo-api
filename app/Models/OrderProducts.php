<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\Order;

class OrderProducts extends Model
{
    use HasFactory;
    
    protected $table = 'order_products';
    protected $guarded = ['id'];

    
    public function products(){
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function order(){
        return $this->belongsTo(Order::class, 'order_id');
    }
}
