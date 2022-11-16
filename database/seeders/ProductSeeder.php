<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductGallery;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = [
            [
                'id'    => 1,
                'name' => 'Product Name',
                'slug' => 'product-name',
                'sku' => '',
                'category_id' => 1,
                'selling_price' => 1500,
                'regular_price' => 2000,
                'description' => 'Product Description',
                'stock' => 10,
                'status' => 1,
                'created_at' => '2021-08-26 06:37:47',
                'updated_at' => '2021-08-26 06:37:47'
            ],
            [
                'id'    =>  2,
                'name' => 'Product Name',
                'slug' => 'product-name',
                'sku' => '',
                'category_id' => 2,
                'selling_price' => 1500,
                'regular_price' => 2000,
                'description' => 'Product Description',
                'stock' => 10,
                'status' => 1,
                'created_at' => '2021-08-26 06:37:47',
                'updated_at' => '2021-08-26 06:37:47'
            ],
            [
                'id'    =>  3,
                'name' => 'Product Name',
                'slug' => 'product-name',
                'sku' => '',
                'category_id' => 3,
                'selling_price' => 1500,
                'regular_price' => 2000,
                'description' => 'Product Description',
                'stock' => 10,
                'status' => 1,
                'created_at' => '2021-08-26 06:37:47',
                'updated_at' => '2021-08-26 06:37:47'
            ],
            [
                'id'    =>  4,
                'name' => 'Product Name',
                'slug' => 'product-name',
                'sku' => '',
                'category_id' => 4,
                'selling_price' => 1500,
                'regular_price' => 2000,
                'description' => 'Product Description',
                'stock' => 10,
                'status' => 1,
                'created_at' => '2021-08-26 06:37:47',
                'updated_at' => '2021-08-26 06:37:47'
            ]
        ];
        
        // Product Add In DataBase
        foreach ($products as $product) {
            Product::insert($product);
        }

        // Product Images 
        $product_image = [
            ['id'=>1, 'product_id'=>'1','image'=>'product/1.jpg','created_at' => '2021-08-26 06:37:47','updated_at' => '2021-08-26 06:37:47'],
            ['id'=>2, 'product_id'=>'1','image'=>'product/5.png','created_at' => '2021-08-26 06:37:47','updated_at' => '2021-08-26 06:37:47'],
            ['id'=>3, 'product_id'=>'2','image'=>'product/2.jpg','created_at' => '2021-08-26 06:37:47','updated_at' => '2021-08-26 06:37:47'],
            ['id'=>4, 'product_id'=>'2','image'=>'product/6.png','created_at' => '2021-08-26 06:37:47','updated_at' => '2021-08-26 06:37:47'],
            ['id'=>5, 'product_id'=>'3','image'=>'product/3.jpg','created_at' => '2021-08-26 06:37:47','updated_at' => '2021-08-26 06:37:47'],
            ['id'=>6, 'product_id'=>'3','image'=>'product/7.png','created_at' => '2021-08-26 06:37:47','updated_at' => '2021-08-26 06:37:47'],
            ['id'=>7, 'product_id'=>'4','image'=>'product/4.jpg','created_at' => '2021-08-26 06:37:47','updated_at' => '2021-08-26 06:37:47'],
            ['id'=>8, 'product_id'=>'4','image'=>'product/8.png','created_at' => '2021-08-26 06:37:47','updated_at' => '2021-08-26 06:37:47'],
        ];

        // Product Add In DataBase
        foreach ($product_image as $images) {
            ProductGallery::insert($images);

            File::copy(public_path('assets/images/seederImages/'.$images['image']), public_path('storage/'.$images['image']));
        }
    }
}
