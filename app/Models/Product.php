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
        'price_detail',
        'units_per_case',
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

    public function getDisplayImageAttribute(): string
    {
        if ($this->image_path && file_exists(public_path('storage/' . $this->image_path))) {
            return asset('storage/' . $this->image_path);
        }

        $lower = strtolower($this->name . ' ' . $this->slug);
        if (str_contains($lower, 'ananas')) {
            return asset('images/fruits/ananas.png');
        }
        if (str_contains($lower, 'mangue')) {
            return asset('images/fruits/mangue.png');
        }
        if (str_contains($lower, 'papaye')) {
            return asset('images/fruits/papaye.png');
        }

        return asset('images/joossi_kinu_final.png');
    }
}
