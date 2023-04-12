<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    public $timestamps = false;

    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'additional',
        'option',
        'price',
        'total_price',
    ];

}
