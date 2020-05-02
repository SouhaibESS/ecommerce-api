<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public $timestamps = false;

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
