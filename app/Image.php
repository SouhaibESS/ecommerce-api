<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    public $timestamps = false;

    protected $fillable = ['product_id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
