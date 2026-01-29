<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'image_path'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->where('status', 'approved');
    }

    public function shops()
    {
        return $this->belongsToMany(Shop::class, 'product_shop')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
}
