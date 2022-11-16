<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'id' => 1,    
                'slug' => 'men',
                'name' => 'Men',
                'status' => 1,
                'created_at' => '2021-08-26 06:37:47',
                'updated_at' => '2021-08-26 06:37:47'
            ],
            [
                'id'    =>  2,
                'slug' => 'woman',
                'name' => 'Woman',
                'status' => 1,
                'created_at' => '2021-08-26 06:37:47',
                'updated_at' => '2021-08-26 06:37:47'
            ],
            [
                'id'    =>  3,
                'slug' => 'electronics',
                'name' => 'Electronics',
                'status' => 1,
                'created_at' => '2021-08-26 06:37:47',
                'updated_at' => '2021-08-26 06:37:47'
            ],
            [
                'id'    =>  4,
                'slug' => 'jwellary',
                'name' => 'Jwellary',
                'status' => 1,
                'created_at' => '2021-08-26 06:37:47',
                'updated_at' => '2021-08-26 06:37:47'
            ]
        ];

        // Category Add Data In DataBase
        foreach ($categories as $category) {
            Category::insert($category);
        }
    }
}
