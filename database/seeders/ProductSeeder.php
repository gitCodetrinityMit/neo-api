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
                'name' => 'Men T-shirt',
                'slug' => 'men-t-shirt',
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
                'name' => 'Woman Skurt',
                'slug' => 'woman-skurt',
                'sku' => '',
                'category_id' => 7,
                'selling_price' => 4500,
                'regular_price' => 5000,
                'description' => 'Product Description',
                'stock' => 20,
                'status' => 1,
                'created_at' => '2021-08-26 06:37:47',
                'updated_at' => '2021-08-26 06:37:47'
            ],
            [
                'id'    =>  3,
                'name' => 'Mobail Accessories',
                'slug' => 'mobail-accessories',
                'sku' => '',
                'category_id' => 118,
                'selling_price' => 6500,
                'regular_price' => 7000,
                'description' => 'Product Description',
                'stock' => 30,
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
            ['id'=>6, 'product_id'=>'3','image'=>'product/7.png','created_at' => '2021-08-26 06:37:47','updated_at' => '2021-08-26 06:37:47']
        ];

        // Product Add In DataBase
        foreach ($product_image as $images) {
            ProductGallery::insert($images);

            File::copy(public_path('assets/images/seederImages/'.$images['image']), public_path('storage/'.$images['image']));
        }
    }
}
