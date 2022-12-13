<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categoryid = [1,2,3,4,5,6,7,8,9,10,11];
        $category = ['Uncategorize Category','Category 1','Category 2','Category 3','Category 4','Category 5','Sub Category 1','Sub Category 2','Sub Category 3','Sub Category 4','Sub Category 5'];
        $categoryparentId = [0,0,0,0,0,0,2,2,3,4,5];

        // Category Add Data In DataBase
        foreach ($category as $k => $cat) {
            $categ = new Category();
            $categ->id = @$categoryid[$k];
            $categ->slug = Str::slug(@$cat);
            $categ->parent_id = @$categoryparentId[$k];
            $categ->name = @$cat;
            $categ->status = 1;
            $categ->save();
        }
    }
}
