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
        'avarie_pct' => 'float',
        'pu_pf' => 'float',
        'pu_gf' => 'float',
        'prime_bio_kg' => 'float',
        'total_credit' => 'float',
    ];

    protected $appends = [
        'total_weight',
        'total_weight_pf',
        'total_weight_gf',
        'poids_marchand_pf',
        'poids_marchand_gf',
        'poids_marchand_total',
        'montant_pf',
        'montant_gf',
        'montant_total',
        'montant_total_prime',
        'net_a_payer',
    ];

    public function weights()
    {
        return $this->hasMany(PurchaseInvoiceWeight::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Récupère tous les poids avec eager loading par défaut
     */
    protected static function booted()
    {
        static::retrieved(function ($invoice) {
            if (!$invoice->relationLoaded('weights')) {
                $invoice->load('weights');
            }
        });
    }

    /**
     * Poids total toutes catégories confondues
     */
    public function getTotalWeightAttribute()
    {
        if (!$this->relationLoaded('weights')) {
            return $this->weights()->sum('weight');
        }
        return $this->weights->sum('weight');
    }

    /**
     * Poids total des petits fruits (PF)
     */
    public function getTotalWeightPfAttribute()
    {
        if (!$this->relationLoaded('weights')) {
            return $this->weights()->where('calibre', 'PF')->sum('weight');
        }
        return $this->weights->where('calibre', 'PF')->sum('weight');
    }

    /**
     * Poids total des gros fruits (GF)
     */
    public function getTotalWeightGfAttribute()
    {
        if (!$this->relationLoaded('weights')) {
            return $this->weights()->where('calibre', 'GF')->sum('weight');
        }
        return $this->weights->where('calibre', 'GF')->sum('weight');
    }

    /**
     * Poids marchand des petits fruits (après déduction avarie)
     */
    public function getPoidsMarchandPfAttribute()
    {
        $avarieFactor = 1 - (($this->avarie_pct ?? 0) / 100);
        return round($this->total_weight_pf * $avarieFactor, 2);
    }

    /**
     * Poids marchand des gros fruits (après déduction avarie)
     */
    public function getPoidsMarchandGfAttribute()
    {
        $avarieFactor = 1 - (($this->avarie_pct ?? 0) / 100);
        return round($this->total_weight_gf * $avarieFactor, 2);
    }

    /**
     * Poids marchand total (après déduction avarie)
     */
    public function getPoidsMarchandTotalAttribute()
    {
        return round($this->poids_marchand_pf + $this->poids_marchand_gf, 2);
    }

    /**
     * Montant pour les petits fruits
     */
    public function getMontantPfAttribute()
    {
        return round($this->poids_marchand_pf * ($this->pu_pf ?? 0), 2);
    }

    /**
     * Montant pour les gros fruits
     */
    public function getMontantGfAttribute()
    {
        return round($this->poids_marchand_gf * ($this->pu_gf ?? 0), 2);
    }

    /**
     * Montant total (PF + GF)
     */
    public function getMontantTotalAttribute()
    {
        return round($this->montant_pf + $this->montant_gf, 2);
    }

    /**
     * Montant total de la prime bio
     */
    public function getMontantTotalPrimeAttribute()
    {
        return round($this->total_weight * ($this->prime_bio_kg ?? 0), 2);
    }

    /**
     * Montant total du crédit
     */
    public function getTotalCreditAttribute()
    {
        return floatval($this->attributes['total_credit'] ?? 0);
    }

    /**
     * Net à payer (montant total + prime - crédit)
     */
    public function getNetAPayerAttribute()
    {
        return round(
            ($this->montant_total + $this->montant_total_prime) - $this->total_credit,
            0
        );
    }

    /**
     * Poids avarié
     */
    public function getPoidsAvarieAttribute()
    {
        return round($this->total_weight * (($this->avarie_pct ?? 0) / 100), 2);
    }

    /**
     * Vérifie si la facture a des poids GF
     */
    public function hasGrosFruits()
    {
        return $this->total_weight_gf > 0;
    }

    /**
     * Vérifie si la facture a des poids PF
     */
    public function hasPetitsFruits()
    {
        return $this->total_weight_pf > 0;
    }

    /**
     * Scope pour filtrer par date
     */
    public function scopeDateBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween('date_invoice', [$startDate, $endDate]);
    }

    /**
     * Scope pour filtrer par zone
     */
    public function scopeZone($query, $zone)
    {
        return $query->where('zone', $zone);
    }

    /**
     * Scope pour filtrer par producteur
     */
    public function scopeProducteur($query, $producteur)
    {
        return $query->where('producteur', 'LIKE', "%{$producteur}%");
    }
}