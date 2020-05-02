<?php

use App\Image;
use App\Product;
use Illuminate\Database\Seeder;

class ImagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = Product::all();

        foreach($products as $product)
        {
            for($i = 0 ; $i < 3 ; $i++)
            {
                if($i == 0)
                {
                    $product->images()->create([
                        'is_main' => 1
                    ]);
                }
                else 
                {
                    $product->images()->create();
                }
            }
        }
    }
}
