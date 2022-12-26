<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductGallery;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

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
                'sku' => Str::random(10),
                'selling_price' => 1500,
                'regular_price' => 2000,
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem  Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
                'short_description' =>  'Contrary to popular belief, Lorem Ipsum is not simply random text. ',
                'stock' => 10,
                'status' => 1,
            ],
            [
                'id'    => 2,
                'name' => 'Product 2',
                'slug' => 'product-2',
                'sku' => Str::random(10),
                'selling_price' => 4000,
                'regular_price' => 5000,
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem  Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
                'short_description' =>  'Contrary to popular belief, Lorem Ipsum is not simply random text.',
                'stock' => 20,
                'status' => 0,
            ],
            [
                'id'    => 3,
                'name' => 'Product 3',
                'slug' => 'product-3',
                'sku' => Str::random(10),
                'selling_price' => 6000,
                'regular_price' => 7000,
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem  Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
                'short_description' =>  'Contrary to popular belief, Lorem Ipsum is not simply random text. ',
                'stock' => 30,
                'status' => 1,
            ],
            [
                'id'    => 4,
                'name' => 'Product 4',
                'slug' => 'product-4',
                'sku' => Str::random(10),
                'selling_price' => 9000,
                'regular_price' => 9500,
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem  Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
                'short_description' =>  'Contrary to popular belief, Lorem Ipsum is not simply random text.',
                'stock' => 40,
                'status' => 0,
            ],
            [
                'id'    => 5,
                'name' => 'Product 5',
                'slug' => 'product-5',
                'sku' => Str::random(10),
                'selling_price' => 9000,
                'regular_price' => 9500,
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem  Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
                'short_description' =>  'Contrary to popular belief, Lorem Ipsum is not simply random text.',
                'stock' => 50,
                'status' => 1,
            ],
            [
                'id'    => 6,
                'name' => 'Sub Category Product 6',
                'slug' => 'sub-category-product-6',
                'sku' => Str::random(10),
                'selling_price' => 8000,
                'regular_price' => 8500,
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem  Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
                'short_description' =>  'Contrary to popular belief, Lorem Ipsum is not simply random text. ',
                'stock' => 60,
                'status' => 1,
            ],
            [
                'id'    => 7,
                'name' => 'Sub Category Product 7',
                'slug' => 'sub-category-product-7',
                'sku' => Str::random(10),
                'selling_price' => 1000,
                'regular_price' => 2000,
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem  Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
                'short_description' =>  'Contrary to popular belief, Lorem Ipsum is not simply random text. ',
                'stock' => 35,
                'status' => 1,
            ],
            [
                'id'    => 8,
                'name' => 'Sub Category Product 8',
                'slug' => 'sub-category-product-8',
                'sku' => Str::random(10),
                'selling_price' => 2500,
                'regular_price' => 3000,
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem  Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
                'short_description' =>  'Contrary to popular belief, Lorem Ipsum is not simply random text.',
                'stock' => 45,
                'status' => 0,
            ],
            [
                'id'    => 9,
                'name' => 'Sub Category Product 9',
                'slug' => 'sub-category-product-9',
                'sku' => Str::random(10),
                'selling_price' => 4500,
                'regular_price' => 5000,
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem  Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
                'short_description' =>  'Contrary to popular belief, Lorem Ipsum is not simply random text. ',
                'stock' => 50,
                'status' => 1,
            ],
            [
                'id'    => 10,
                'name' => 'Sub Category Product 10',
                'slug' => 'sub-category-product-10',
                'sku' => Str::random(10),
                'selling_price' => 4500,
                'regular_price' => 5000,
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem  Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
                'short_description' =>  'Contrary to popular belief, Lorem Ipsum is not simply random text. ',
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
            ['id'=>1, 'product_id'=>'1','image'=>'product/1.jpg'],
            ['id'=>2, 'product_id'=>'1','image'=>'product/2.jpg'],
            ['id'=>3, 'product_id'=>'2','image'=>'product/3.jpg'],
            ['id'=>4, 'product_id'=>'2','image'=>'product/4.jpg'],
            ['id'=>5, 'product_id'=>'3','image'=>'product/5.png'],
            ['id'=>6, 'product_id'=>'3','image'=>'product/6.png'],
            ['id'=>7, 'product_id'=>'4','image'=>'product/7.png'],
            ['id'=>8, 'product_id'=>'4','image'=>'product/8.jpg'],
            ['id'=>9, 'product_id'=>'5','image'=>'product/9.jpg'],
            ['id'=>10, 'product_id'=>'5','image'=>'product/10.jpg'],
            ['id'=>11, 'product_id'=>'6','image'=>'product/11.jpg'],
            ['id'=>12, 'product_id'=>'6','image'=>'product/12.jpg'],
            ['id'=>13, 'product_id'=>'7','image'=>'product/13.png'],
            ['id'=>14, 'product_id'=>'7','image'=>'product/14.png'],
            ['id'=>15, 'product_id'=>'8','image'=>'product/15.png'],
            ['id'=>16, 'product_id'=>'8','image'=>'product/16.png'],
            ['id'=>17, 'product_id'=>'9','image'=>'product/17.png'],
            ['id'=>18, 'product_id'=>'9','image'=>'product/18.png'],
            ['id'=>19, 'product_id'=>'10','image'=>'product/19.png'],
            ['id'=>20, 'product_id'=>'10','image'=>'product/20.png']
        ];

        // Product Add In DataBase
        foreach ($product_image as $images) {
            File::copy(public_path('assets/images/seederImages/'.$images['image']), public_path('storage/'.$images['image']));
            // ProductGallery::insert($images);
            ProductGallery::create($images);

        }

        // ProductrCategory Add In DataBase
        $product_category_id = [1,2,3,4,5,6,7,8,9,10];
        $product_id = [1,2,3,4,5,6,7,8,9,10];
        $category_id = [1,1,2,2,3,3,4,4,5,5];
        foreach($product_category_id as $k => $value){    
            $data = new ProductCategory();
            $data->id = $product_category_id[$k];
            $data->product_id = $product_id[$k];
            $data->category_id = $category_id[$k];
            $data->save();
        }
    }
}
