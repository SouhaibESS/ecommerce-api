<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'product_id',
        'client_name',
        'client_email',
        'client_phone_number',
        'ordered_quantity',
        'ordered'    
    ];

    public function product() 
    {
        return $this->belongsTo(Product::class);
    }
}
