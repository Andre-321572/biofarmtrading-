<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'bon_no',
        'date_invoice',
        'prefecture',
        'zone',
        'chauffeur',
        'fruit',
        'op',
        'producteur',
        'code_parcelle_matricule',
        'quantite_estimee',
        'pu',
        'prime_bio_kg',
        'poids_avarie',
        'poids_marchand',
        'net_payer_lettre',
        'user_id',
    ];

    protected $casts = [
        'date_invoice' => 'date',
    ];

    public function weights()
    {
        return $this->hasMany(PurchaseInvoiceWeight::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTotalWeightAttribute()
    {
        return $this->weights()->sum('weight');
    }

    public function getMontantTotalAttribute()
    {
        return $this->total_weight * $this->pu;
    }

    public function getTotalCreditAttribute()
    {
        return ($this->poids_avarie ?? 0) + ($this->poids_marchand ?? 0);
    }

    public function getNetAPayerAttribute()
    {
        return $this->montant_total - $this->total_credit;
    }

    public function getMontantTotalPrimeAttribute()
    {
        return $this->total_weight * ($this->prime_bio_kg ?? 0);
    }
}
