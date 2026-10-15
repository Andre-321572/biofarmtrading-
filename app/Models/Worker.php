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
        'hired_at',
        'is_new',
        'hourly_rate',
    ];

    protected $casts = [
        'hired_at' => 'date',
        'is_new' => 'boolean',
        'hourly_rate' => 'float',
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
     * Date d'embauche effective de l'ouvrier (ou date de création si non renseignée).
     */
    public function getHiredAt(): Carbon
    {
        if ($this->hired_at) {
            return Carbon::parse($this->hired_at)->startOfDay();
        }

        if ($this->created_at) {
            return Carbon::parse($this->created_at)->startOfDay();
        }

        return Carbon::now()->startOfDay();
    }

    /**
     * Date de fin de la période d'essai de 2 semaines (14 jours).
     */
    public function getTrialEndsAt(): Carbon
    {
        return $this->getHiredAt()->copy()->addDays(14)->endOfDay();
    }

    /**
     * Vérifie si l'ouvrier était en période d'essai (14 jours) à une date précise.
     */
    public function isInTrialOnDate(Carbon|string $date): bool
    {
        // 1. Si titularisé explicitement (is_new = false)
        if ($this->is_new === false || $this->is_new === 0 || $this->is_new === '0') {
            return false;
        }

        // 2. Si forcé en nouveau (is_new = true)
        if ($this->is_new === true || $this->is_new === 1 || $this->is_new === '1') {
            return true;
        }

        // 3. Détection automatique : pendant les 14 jours suivant l'embauche
        $dateCarbon = Carbon::parse($date)->startOfDay();
        $hiredAt = $this->getHiredAt();
        $trialEndsAt = $this->getTrialEndsAt();

        return $dateCarbon->lessThanOrEqualTo($trialEndsAt) && $dateCarbon->greaterThanOrEqualTo($hiredAt);
    }

    /**
     * Détermine si l'ouvrier est actuellement en période d'essai.
     */
    public function isCurrentlyInTrial(): bool
    {
        return $this->isInTrialOnDate(Carbon::now());
    }

    /**
     * Nombre de jours restants dans la période d'essai de 14 jours (0 si terminée).
     */
    public function getTrialDaysRemaining(): int
    {
        if (!$this->isCurrentlyInTrial()) {
            return 0;
        }

        $now = Carbon::now()->startOfDay();
        $trialEnd = $this->getTrialEndsAt()->startOfDay();

        $diff = $now->diffInDays($trialEnd, false);
        return max(0, (int) $diff);
    }

    /**
     * Détermine si l'ouvrier est considéré comme "Nouveau" (en période d'essai ou flag is_new actif).
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

        // 3. Période d'essai automatique de 14 jours
        return $this->isCurrentlyInTrial();
    }

    /**
     * Détermine le taux horaire effectif de base de l'ouvrier :
     * 1. S'il a un taux horaire personnalisé sur sa fiche, on l'utilise.
     * 2. S'il est considéré comme "Nouveau" (période d'essai), on utilise le taux des nouveaux (218.75 FCFA).
     * 3. Sinon, on utilise le taux standard des anciens ouvriers (275 FCFA).
     */
    public function getEffectiveHourlyRate(?float $regularRate = null, ?float $trialRate = null): float
    {
        if ($this->hourly_rate !== null && $this->hourly_rate > 0) {
            return (float) $this->hourly_rate;
        }

        $trialRate = $trialRate ?? \App\Services\PayrollService::getNewHourlyRate();
        $regularRate = $regularRate ?? \App\Services\PayrollService::getHourlyRate();

        return $this->isNew() ? $trialRate : $regularRate;
    }

    /**
     * Scope pour filtrer les nouveaux ouvriers (en cours d'essai ou flag is_new).
     */
    public function scopeNewWorkers($query)
    {
        $cutoff = Carbon::now()->subDays(14);

        return $query->where('is_new', true)
            ->orWhere(function ($q) use ($cutoff) {
                $q->where(function ($sub) use ($cutoff) {
                    $sub->whereNotNull('hired_at')
                        ->where('hired_at', '>=', $cutoff->toDateString());
                })->orWhere(function ($sub) use ($cutoff) {
                    $sub->whereNull('hired_at')
                        ->where('created_at', '>=', $cutoff);
                });
            });
    }
}
