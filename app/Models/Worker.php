<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Worker extends Model
{
    protected $fillable = [
        'first_name', 
        'last_name', 
        'position', 
        'shift',
        'is_new',
        'hourly_rate',
    ];

    protected $casts = [
        'is_new' => 'boolean',
        'hourly_rate' => 'integer',
    ];

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function bonuses()
    {
        return $this->hasMany(WorkerBonus::class);
    }

    /**
     * Détermine si l'ouvrier est considéré comme "Nouveau".
     * Est nouveau si le drapeau is_new est actif ou s'il a été ajouté il y a moins de 35 jours.
     */
    public function isNew(): bool
    {
        // 1. Si le flag is_new est explicitement à true -> Nouveau
        if ($this->is_new === true || $this->is_new === 1 || $this->is_new === '1') {
            return true;
        }

        // 2. Si le flag is_new est explicitement à false -> Ancien (titularisé)
        if ($this->is_new === false || $this->is_new === 0 || $this->is_new === '0') {
            return false;
        }

        // 3. Sinon (si non spécifié ou null), détection automatique par date de création (35 jours)
        if ($this->created_at) {
            return $this->created_at->diffInDays(Carbon::now()) <= 35;
        }

        return false;
    }

    /**
     * Détermine le taux horaire effectif de l'ouvrier :
     * 1. S'il a un taux horaire personnalisé sur sa fiche, on l'utilise.
     * 2. S'il est considéré comme "Nouveau", on utilise le taux horaire des nouveaux ouvriers.
     * 3. Sinon, on utilise le taux standard des anciens ouvriers.
     */
    public function getEffectiveHourlyRate(?int $regularRate = null, ?int $newRate = null): int
    {
        if ($this->hourly_rate !== null && $this->hourly_rate > 0) {
            return (int) $this->hourly_rate;
        }

        $newRate = $newRate ?? \App\Services\PayrollService::getNewHourlyRate();
        $regularRate = $regularRate ?? \App\Services\PayrollService::getHourlyRate();

        return $this->isNew() ? $newRate : $regularRate;
    }

    /**
     * Scope pour filtrer les nouveaux ouvriers.
     */
    public function scopeNewWorkers($query)
    {
        return $query->where('is_new', true)
            ->orWhere('created_at', '>=', Carbon::now()->subDays(35));
    }
}
