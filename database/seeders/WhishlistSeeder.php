<?php

namespace Database\Seeders;

use App\Models\Wishlist;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WhishlistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $wishlists = [
            [
                'user_id'   =>  1,
                'product_id'    =>  1,
            ],[
                'user_id'   =>  1,
                'product_id'    =>  2,
            ],[
                'user_id'   =>  1,
                'product_id'    =>  3,
            ],
            [
                'user_id'   =>  3,
                'product_id'    =>  1,
            ],
            [
                'user_id'   =>  3,
                'product_id'    =>  2,
            ]
        ];
        foreach ($wishlists as $wishlist) {
            Wishlist::create($wishlist);
        }
    }
}
