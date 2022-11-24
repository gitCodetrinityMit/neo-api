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
                'name' => 'Product 1',
                'slug' => 'product-1',
                'sku' => '',
                'category_id' => 1,
                'selling_price' => 1500,
                'regular_price' => 2000,
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem  Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
                'stock' => 10,
                'status' => 1,
            ],
            [
                'id'    => 2,
                'name' => 'Product 2',
                'slug' => 'product-2',
                'sku' => '',
                'category_id' => 2,
                'selling_price' => 4000,
                'regular_price' => 5000,
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem  Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
                'stock' => 20,
                'status' => 0,
            ],
            [
                'id'    => 3,
                'name' => 'Product 3',
                'slug' => 'product-3',
                'sku' => '',
                'category_id' => 3,
                'selling_price' => 6000,
                'regular_price' => 7000,
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem  Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
                'stock' => 30,
                'status' => 1,
            ],
            [
                'id'    => 4,
                'name' => 'Product 4',
                'slug' => 'product-4',
                'sku' => '',
                'category_id' => 4,
                'selling_price' => 9000,
                'regular_price' => 9500,
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem  Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
                'stock' => 40,
                'status' => 0,
            ],
            [
                'id'    => 5,
                'name' => 'Product 5',
                'slug' => 'product-5',
                'sku' => '',
                'category_id' => 5,
                'selling_price' => 9000,
                'regular_price' => 9500,
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem  Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
                'stock' => 50,
                'status' => 1,
            ],
            [
                'id'    => 6,
                'name' => 'Sub Category Product 6',
                'slug' => 'sub-category-product-6',
                'sku' => '',
                'category_id' => 6,
                'selling_price' => 8000,
                'regular_price' => 8500,
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem  Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
                'stock' => 60,
                'status' => 1,
            ],
            [
                'id'    => 7,
                'name' => 'Sub Category Product 7',
                'slug' => 'sub-category-product-7',
                'sku' => '',
                'category_id' => 7,
                'selling_price' => 1000,
                'regular_price' => 2000,
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem  Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
                'stock' => 35,
                'status' => 1,
            ],
            [
                'id'    => 8,
                'name' => 'Sub Category Product 8',
                'slug' => 'sub-category-product-8',
                'sku' => '',
                'category_id' => 8,
                'selling_price' => 2500,
                'regular_price' => 3000,
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem  Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
                'stock' => 45,
                'status' => 0,
            ],
            [
                'id'    => 9,
                'name' => 'Sub Category Product 9',
                'slug' => 'sub-category-product-9',
                'sku' => '',
                'category_id' => 9,
                'selling_price' => 4500,
                'regular_price' => 5000,
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem  Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
                'stock' => 50,
                'status' => 1,
            ],
            [
                'id'    => 10,
                'name' => 'Sub Category Product 10',
                'slug' => 'sub-category-product-10',
                'sku' => '',
                'category_id' => 10,
                'selling_price' => 4500,
                'regular_price' => 5000,
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem  Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
                'stock' => 10,
                'status' => 0,
            ]
        ];
        
        // Product Add In DataBase
        foreach ($products as $product) {
            Product::create($product);
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
            ProductGallery::create($images);

            File::copy(public_path('assets/images/seederImages/'.$images['image']), public_path('storage/'.$images['image']));
        }
    }
}
