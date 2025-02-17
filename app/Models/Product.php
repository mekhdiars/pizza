<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'type',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2'
    ];
}
