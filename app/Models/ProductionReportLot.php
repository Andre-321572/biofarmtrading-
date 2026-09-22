<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionReportLot extends Model
{
    use HasFactory;

    protected $fillable = [
        'production_report_id',
        'numero_lot',
        'type_produit',
        'quantite_declayee_kg',
        'poids_rcl_kg',
        'poids_mcl_kg',
        'poids_rps_kg',
        'poids_mps_kg',
        'poids_lpa_kg',
        'sachets_2kg',
        'sachets_1kg',
        'sachets_500g',
        'sachets_rcl_2kg',
        'sachets_rcl_1kg',
        'sachets_mcl_2kg',
        'sachets_mcl_1kg',
        'sachets_rps_2kg',
        'sachets_rps_1kg',
        'sachets_mps_2kg',
        'sachets_mps_1kg',
        'sachets_lpa_2kg',
        'sachets_lpa_1kg',
        'sachets_lpa_100g',
        'sachets_ttpm_1kg',
        'type_coupe',
        'dechets_kg',
        'cl_ttpm_kg',
        'pa_kg',
        'pelage_info',
        'notes',
    ];

    protected $casts = [
        'quantite_declayee_kg' => 'decimal:2',
        'poids_rcl_kg' => 'decimal:2',
        'poids_mcl_kg' => 'decimal:2',
        'poids_rps_kg' => 'decimal:2',
        'poids_mps_kg' => 'decimal:2',
        'poids_lpa_kg' => 'decimal:2',
        'sachets_2kg' => 'integer',
        'sachets_1kg' => 'integer',
        'sachets_500g' => 'integer',
        'sachets_rcl_2kg' => 'integer',
        'sachets_rcl_1kg' => 'integer',
        'sachets_mcl_2kg' => 'integer',
        'sachets_mcl_1kg' => 'integer',
        'sachets_rps_2kg' => 'integer',
        'sachets_rps_1kg' => 'integer',
        'sachets_mps_2kg' => 'integer',
        'sachets_mps_1kg' => 'integer',
        'sachets_lpa_2kg' => 'integer',
        'sachets_lpa_1kg' => 'integer',
        'sachets_lpa_100g' => 'integer',
        'sachets_ttpm_1kg' => 'integer',
        'dechets_kg' => 'decimal:2',
        'cl_ttpm_kg' => 'decimal:2',
        'pa_kg' => 'decimal:2',
    ];

    public function productionReport()
    {
        return $this->belongsTo(ProductionReport::class);
    }

    public function getTotalSachetsAttribute(): int
    {
        return $this->sachets_2kg + $this->sachets_1kg + $this->sachets_500g
            + $this->sachets_rcl_2kg + $this->sachets_rcl_1kg
            + $this->sachets_mcl_2kg + $this->sachets_mcl_1kg
            + $this->sachets_rps_2kg + $this->sachets_rps_1kg
            + $this->sachets_mps_2kg + $this->sachets_mps_1kg
            + $this->sachets_lpa_2kg + $this->sachets_lpa_1kg + $this->sachets_lpa_100g
            + $this->sachets_ttpm_1kg;
    }
}
