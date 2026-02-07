<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArrivageDetail extends Model
{
    protected $fillable = [
        'arrivage_id',
        'fruit',
        'variete',
        'poids'
    ];

    public function arrivage()
    {
        return $this->belongsTo(Arrivage::class);
    }
}
