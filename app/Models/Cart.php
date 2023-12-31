<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Cart extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id', 'cookie_id', 'product_id', 'user_id', 'quantity',
    ];

    protected $with = [
        'product',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    protected static function booted()
    {
        /*
            Events:
            creating, created, updateing, updated, saving, saved, deleteing, deleted, restoring, restored
        */
        static::creating(function(Cart $cart){
            $cart->id = Str::uuid();
        });
    }
}
