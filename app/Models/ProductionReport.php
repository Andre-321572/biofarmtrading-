<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'date_rapport',
        'nombre_ouvriers',
        'nombre_ouvriers_absents',
        'nombre_ouvriers_repos',
        'nombre_couteaux_debut',
        'nombre_couteaux_fin',
        'nombre_ciseaux_debut',
        'nombre_ciseaux_fin',
        'lots_peles',
        'user_id',
        'notes',
    ];

    protected $casts = [
        'date_rapport' => 'date',
        'nombre_ouvriers' => 'integer',
        'nombre_ouvriers_absents' => 'integer',
        'nombre_ouvriers_repos' => 'integer',
        'nombre_couteaux_debut' => 'integer',
        'nombre_couteaux_fin' => 'integer',
        'nombre_ciseaux_debut' => 'integer',
        'nombre_ciseaux_fin' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lots()
    {
        return $this->hasMany(ProductionReportLot::class);
    }

    public function getTotalDeclayeAttribute(): float
    {
        return (float) $this->lots->sum('quantite_declayee_kg');
    }

    public function getTotalPoidsRclKgAttribute(): float { return (float) $this->lots->sum('poids_rcl_kg'); }
    public function getTotalPoidsMclKgAttribute(): float { return (float) $this->lots->sum('poids_mcl_kg'); }
    public function getTotalPoidsRpsKgAttribute(): float { return (float) $this->lots->sum('poids_rps_kg'); }
    public function getTotalPoidsMpsKgAttribute(): float { return (float) $this->lots->sum('poids_mps_kg'); }
    public function getTotalPoidsLpaKgAttribute(): float { return (float) $this->lots->sum('poids_lpa_kg'); }

    public function getTotalSachets2kgAttribute(): int
    {
        return (int) ($this->lots->sum('sachets_2kg') + $this->lots->sum('sachets_mcl_2kg') + $this->lots->sum('sachets_rcl_2kg') + $this->lots->sum('sachets_rps_2kg') + $this->lots->sum('sachets_mps_2kg') + $this->lots->sum('sachets_lpa_2kg'));
    }

    public function getTotalSachets1kgAttribute(): int
    {
        return (int) ($this->lots->sum('sachets_1kg') + $this->lots->sum('sachets_mcl_1kg') + $this->lots->sum('sachets_rcl_1kg') + $this->lots->sum('sachets_rps_1kg') + $this->lots->sum('sachets_mps_1kg') + $this->lots->sum('sachets_lpa_1kg'));
    }

    public function getTotalSachets500gAttribute(): int
    {
        return (int) $this->lots->sum('sachets_500g');
    }

    public function getTotalSachetsRcl2kgAttribute(): int { return (int) $this->lots->sum('sachets_rcl_2kg'); }
    public function getTotalSachetsRcl1kgAttribute(): int { return (int) $this->lots->sum('sachets_rcl_1kg'); }
    public function getTotalSachetsMcl2kgAttribute(): int { return (int) ($this->lots->sum('sachets_mcl_2kg') + $this->lots->sum('sachets_2kg')); }
    public function getTotalSachetsMcl1kgAttribute(): int { return (int) ($this->lots->sum('sachets_mcl_1kg') + $this->lots->sum('sachets_1kg')); }
    public function getTotalSachetsRps2kgAttribute(): int { return (int) $this->lots->sum('sachets_rps_2kg'); }
    public function getTotalSachetsRps1kgAttribute(): int { return (int) $this->lots->sum('sachets_rps_1kg'); }
    public function getTotalSachetsMps2kgAttribute(): int { return (int) $this->lots->sum('sachets_mps_2kg'); }
    public function getTotalSachetsMps1kgAttribute(): int { return (int) $this->lots->sum('sachets_mps_1kg'); }
    public function getTotalSachetsLpa2kgAttribute(): int { return (int) $this->lots->sum('sachets_lpa_2kg'); }
    public function getTotalSachetsLpa1kgAttribute(): int { return (int) $this->lots->sum('sachets_lpa_1kg'); }
    public function getTotalSachetsLpa100gAttribute(): int { return (int) $this->lots->sum('sachets_lpa_100g'); }

    public function getTotalSachetsAttribute(): int
    {
        return $this->total_sachets_2kg + $this->total_sachets_1kg + $this->total_sachets_500g + $this->total_sachets_lpa_100g;
    }

    public function getTotalDechetsAttribute(): float
    {
        return (float) $this->lots->sum('dechets_kg');
    }

    public function getTotalTtpmAttribute(): float
    {
        return (float) $this->lots->sum('cl_ttpm_kg');
    }

    public function getGroupedLotsAttribute()
    {
        return $this->lots->groupBy('type_produit');
    }
}
