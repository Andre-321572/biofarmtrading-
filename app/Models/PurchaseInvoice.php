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
        'calibre',
        'pu_pf',
        'pu_gf',
        'prime_bio_kg',
        'avarie_pct',
        'poids_avarie',
        'poids_marchand',
        'total_credit',
        'signature_resp',
        'signature_prod',
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

    public function getTotalWeightPfAttribute()
    {
        return $this->weights()->where('calibre', 'PF')->sum('weight');
    }

    public function getTotalWeightGfAttribute()
    {
        return $this->weights()->where('calibre', 'GF')->sum('weight');
    }

    public function getPoidsMarchandPfAttribute()
    {
        return round($this->total_weight_pf * (1 - ($this->avarie_pct ?? 0) / 100), 2);
    }

    public function getPoidsMarchandGfAttribute()
    {
        return round($this->total_weight_gf * (1 - ($this->avarie_pct ?? 0) / 100), 2);
    }

    public function getMontantPfAttribute()
    {
        return round($this->poids_marchand_pf * ($this->pu_pf ?? 0), 2);
    }

    public function getMontantGfAttribute()
    {
        return round($this->poids_marchand_gf * ($this->pu_gf ?? 0), 2);
    }

    public function getMontantTotalAttribute()
    {
        return $this->montant_pf + $this->montant_gf;
    }

    public function getTotalCreditAttribute()
    {
        // Retourne la valeur stockée en base (saisie manuellement)
        return $this->attributes['total_credit'] ?? 0;
    }

    public function getNetAPayerAttribute()
    {
        return ($this->montant_total + $this->montant_total_prime) - $this->total_credit;
    }

    public function getMontantTotalPrimeAttribute()
    {
        return $this->total_weight * ($this->prime_bio_kg ?? 0);
    }
}
