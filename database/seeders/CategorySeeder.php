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
        $categoryid = [1,7,9,10,32,33,42,43,46,47,102,103,104,106,107,117,118];
        $category = ['men','women','tops','Bottoms','Tops','Bottoms','Shirts','Jackets','Jeans','Shorts','Shirts','Jackets','Dresses','Shorts','Skirts','jeans','accessories'];
        $categoryparentId = [0,0,1,1,7,7,9,9,10,10,32,32,32,33,33,33,0];

        // Category Add Data In DataBase
        foreach ($category as $k => $cat) {
            $categ = new Category();
            $categ->id = @$categoryid[$k];
            $categ->slug = @$cat;
            $categ->parent_id = @$categoryparentId[$k];
            $categ->name = @$cat;
            $categ->status = 1;
            $categ->save();
        }
    }
}
