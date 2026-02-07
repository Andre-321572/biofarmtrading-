<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Arrivage extends Model
{
    protected $fillable = [
        'chauffeur',
        'matricule_camion',
        'date_arrivage',
        'zone_provenance',
        'user_id'
    ];

    protected $casts = [
        'date_arrivage' => 'date',
    ];

    public function details()
    {
        return $this->hasMany(ArrivageDetail::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Calculs des totaux - SÉPARÉS PAR TYPE
    public function getTotalAnanasCayenneAttribute()
    {
        return $this->details()
            ->where('fruit', 'ananas')
            ->where('variete', 'cayenne_lisse')
            ->sum('poids');
    }

    public function getTotalAnanasBrazaAttribute()
    {
        return $this->details()
            ->where('fruit', 'ananas')
            ->where('variete', 'braza')
            ->sum('poids');
    }

    // Total de TOUS les ananas (Cayenne + Braza)
    public function getTotalAnanasAttribute()
    {
        return $this->total_ananas_cayenne + $this->total_ananas_braza;
    }

    // Total des papayes - SÉPARÉ
    public function getTotalPapayeAttribute()
    {
        return $this->details()
            ->where('fruit', 'papaye')
            ->sum('poids');
    }

    // Total général (pour information)
    public function getTotalGeneralAttribute()
    {
        return $this->details()->sum('poids');
    }
}
