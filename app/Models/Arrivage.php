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

    public function getFruitLabelAttribute()
    {
        $firstDetail = $this->details()->first();
        if (!$firstDetail) return '-';
        
        $fruit = $firstDetail->fruit;
        $variete = $firstDetail->variete;
        
        if ($fruit === 'ananas') {
            return 'Ananas ' . ($variete === 'braza' ? 'Braza' : 'Cayenne');
        }
        
        return ucfirst($fruit);
    }

    public function getBonRefAttribute()
    {
        $firstDetail = $this->details->first();
        $fruit_c = $firstDetail ? strtoupper($firstDetail->fruit) : 'DIV';
        
        if (str_contains($fruit_c, 'ANANAS')) $fruit_c = 'ANAS';
        elseif (str_contains($fruit_c, 'PAPAYE')) $fruit_c = 'PAP';
        elseif (str_contains($fruit_c, 'BANANE')) $fruit_c = 'BAN';
        elseif (str_contains($fruit_c, 'MANGUE')) $fruit_c = 'MAN';
        else $fruit_c = substr($fruit_c, 0, 3);
        
        $year = $this->date_arrivage ? $this->date_arrivage->format('Y') : date('Y');
        
        return str_pad($this->id, 3, '0', STR_PAD_LEFT) . '/' . $fruit_c . '/' . $year;
    }

    // Total général (pour information)
    public function getTotalGeneralAttribute()
    {
        return $this->details()->sum('poids');
    }
}
