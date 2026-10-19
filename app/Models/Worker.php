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
        'job_position_id',
        'shift',
        'hired_at',
        'tenured_at',
        'is_new',
        'hourly_rate',
    ];

    protected $casts = [
        'hired_at' => 'date',
        'tenured_at' => 'date',
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

    public function jobPosition()
    {
        return $this->belongsTo(JobPosition::class, 'job_position_id');
    }

    public function employee()
    {
        return $this->hasOne(Employee::class, 'worker_id');
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
     * Date de fin de la période d'essai de 15 jours.
     * Exemple : un ouvrier embauché le 1er du mois a 15 jours d'essai (du 1er au 15 inclus).
     * Dès le 16ème jour (J+16), il bascule automatiquement au statut ancien titularisé.
     */
    public function getTrialEndsAt(): Carbon
    {
        return $this->getHiredAt()->copy()->addDays(14)->endOfDay();
    }

    /**
     * Vérifie si l'ouvrier était en période d'essai (15 jours) à une date précise.
     * Règle métier :
     * Les nouveaux ouvriers sont payés en deux temps :
     * 1. Pendant les 15 premiers jours d'essai à compter de l'embauche -> taux d'essai (218,75 FCFA).
     * 2. Dès le 16ème jour (ou à partir de la date de titularisation) -> taux standard des anciens (275 FCFA).
     * Titulariser un ouvrier comme ancien NE DOIT JAMAIS recalculer rétroactivement sa période d'essai passée !
     */
    public function isInTrialOnDate(Carbon|string $date): bool
    {
        $dateCarbon = Carbon::parse($date)->startOfDay();
        $hiredAt = $this->getHiredAt();

        // 1. Avant la date d'embauche, pas d'essai
        if ($dateCarbon->lessThan($hiredAt)) {
            return false;
        }

        $trialEndsAt = $this->getTrialEndsAt();

        // 2. Si une date de titularisation explicite existe (tenured_at) :
        // Dès le jour de titularisation, l'ouvrier n'est plus en essai
        if ($this->tenured_at) {
            $tenuredAt = Carbon::parse($this->tenured_at)->startOfDay();
            if ($dateCarbon->greaterThanOrEqualTo($tenuredAt)) {
                return false;
            }
            return $dateCarbon->lessThanOrEqualTo($trialEndsAt);
        }

        // 3. Si le flag is_new a été explicitement forcé à true par la RH (maintien de l'essai)
        if ($this->is_new === true || $this->is_new === 1 || $this->is_new === '1') {
            return true;
        }

        // 4. Détection automatique : pendant les 15 premiers jours à compter de l'embauche
        // (même si is_new = false a été coché, la période d'essai effectuée reste payée au taux d'essai)
        return $dateCarbon->lessThanOrEqualTo($trialEndsAt);
    }

    /**
     * Détermine si l'ouvrier est actuellement en période d'essai.
     */
    public function isCurrentlyInTrial(): bool
    {
        if ($this->tenured_at && Carbon::parse($this->tenured_at)->startOfDay()->lessThanOrEqualTo(Carbon::now()->startOfDay())) {
            return false;
        }

        if ($this->is_new === false || $this->is_new === 0 || $this->is_new === '0') {
            return false;
        }

        $now = Carbon::now()->startOfDay();
        $hiredAt = $this->getHiredAt();
        $trialEndsAt = $this->getTrialEndsAt();

        return $now->greaterThanOrEqualTo($hiredAt) && $now->lessThanOrEqualTo($trialEndsAt);
    }

    /**
     * Nombre de jours restants dans la période d'essai de 15 jours (0 si terminée).
     */
    public function getTrialDaysRemaining(): int
    {
        if (!$this->isCurrentlyInTrial()) {
            return 0;
        }

        $now = Carbon::now()->startOfDay();
        $trialEnd = $this->getTrialEndsAt()->startOfDay();

        $diff = $now->diffInDays($trialEnd, false);
        return max(0, (int) $diff + 1);
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

        // 2. Si titularisé explicitement (tenured_at défini ou is_new === false)
        if ($this->tenured_at && Carbon::parse($this->tenured_at)->startOfDay()->lessThanOrEqualTo(Carbon::now()->startOfDay())) {
            return false;
        }

        if ($this->is_new === false || $this->is_new === 0 || $this->is_new === '0') {
            return false;
        }

        // 3. Période d'essai automatique de 15 jours
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
     * Scope pour filtrer les ouvriers actuellement nouveaux (en cours des 15 jours d'essai ou flag is_new actif).
     */
    public function scopeNewWorkers($query)
    {
        $cutoff = Carbon::now()->subDays(14)->startOfDay();

        return $query->where(function ($q) use ($cutoff) {
            $q->where('is_new', true)
              ->orWhere(function ($sub) use ($cutoff) {
                  $sub->whereNull('is_new')
                      ->whereNull('tenured_at')
                      ->where(function ($dateSub) use ($cutoff) {
                          $dateSub->where(function ($h) use ($cutoff) {
                              $h->whereNotNull('hired_at')
                                ->where('hired_at', '>=', $cutoff->toDateString());
                          })->orWhere(function ($c) use ($cutoff) {
                              $c->whereNull('hired_at')
                                ->where('created_at', '>=', $cutoff);
                          });
                      });
              });
        })->where(function ($q) {
            $q->where('is_new', '!=', false)
              ->orWhereNull('is_new');
        })->whereNull('tenured_at');
    }
}
