<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\User;
use App\Models\OrderProducts;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';
    protected $primarykey = 'id';
    protected $guarded = ['id'];

    // public function products()
    // {
    //     return $this->belongsTo(Product::class);
    // }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderProduct(){
        return $this->hasMany(OrderProducts::class, 'order_id','id');
    }
}
