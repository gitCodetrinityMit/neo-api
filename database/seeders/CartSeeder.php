<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cart;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $carts = [
            [
                'user_id'   =>  1,
                'product_id'    =>1,
            ],
            [
                'user_id'   =>  1,
                'product_id'   =>   2, 
            ],
            [
                'user_id'   =>  2,
                'product_id'    =>  3,
            ],
            [
                'user_id'   =>  2,
                'product_id'    =>  4,
            ]
        ];
        
        foreach ($carts as $cart_value) {
            Cart::create($cart_value);
        }
    }
}
