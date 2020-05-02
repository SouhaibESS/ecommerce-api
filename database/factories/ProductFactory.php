<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Image;
use App\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name' => 'product',
        'rating' => $faker->numberBetween(2,5),
        'price' => $faker->randomFloat(2, 0, 10000),
        'description' => $faker->text(),
        'quantity' => $faker->numberBetween(20,50)
    ];
});
