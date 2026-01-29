<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $fillable = ['name', 'address', 'phone'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_shop')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    public function managers()
    {
        return $this->hasMany(User::class);
    }
}
