<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_position_id',
        'matricule',
        'first_name',
        'last_name',
        'gender',
        'phone',
        'email',
        'address',
        'national_id',
        'contract_type',
        'hired_at',
        'monthly_salary',
        'status',
        'emergency_contact_name',
        'emergency_contact_phone',
        'notes',
    ];

    protected $casts = [
        'hired_at' => 'date',
        'monthly_salary' => 'float',
    ];

    /**
     * Boot model: auto-génère un matricule propre si non fourni.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($employee) {
            if (empty($employee->matricule)) {
                $count = static::max('id') + 1;
                $employee->matricule = 'EMP-' . str_pad((string)$count, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    /**
     * Poste occupé par l'employé.
     */
    public function jobPosition()
    {
        return $this->belongsTo(JobPosition::class, 'job_position_id');
    }

    /**
     * Nom complet du membre du personnel.
     */
    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    /**
     * Normalise la valeur du salaire mensuel lors de l'assignation (support de la virgule décimale).
     */
    public function setMonthlySalaryAttribute($value)
    {
        if (is_string($value)) {
            $value = str_replace(',', '.', trim($value));
        }
        $this->attributes['monthly_salary'] = is_numeric($value) ? (float)$value : null;
    }

    /**
     * Badge de statut avec styles Tailwind.
     */
    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'actif' => ['label' => 'Actif', 'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200 ring-emerald-600/20'],
            'conge' => ['label' => 'En congé', 'class' => 'bg-amber-50 text-amber-700 border-amber-200 ring-amber-600/20'],
            'suspendu' => ['label' => 'Suspendu', 'class' => 'bg-rose-50 text-rose-700 border-rose-200 ring-rose-600/20'],
            'demissionnaire' => ['label' => 'Démissionnaire', 'class' => 'bg-slate-100 text-slate-700 border-slate-200 ring-slate-600/20'],
            'licencie' => ['label' => 'Licencié', 'class' => 'bg-red-50 text-red-700 border-red-200 ring-red-600/20'],
            default => ['label' => ucfirst($this->status ?? 'Inconnu'), 'class' => 'bg-gray-100 text-gray-700 border-gray-200'],
        };
    }

    /**
     * Badge pour le type de contrat.
     */
    public function getContractBadgeAttribute(): array
    {
        return match ($this->contract_type) {
            'CDI' => ['label' => 'CDI', 'class' => 'bg-blue-50 text-blue-700 border-blue-200'],
            'CDD' => ['label' => 'CDD', 'class' => 'bg-purple-50 text-purple-700 border-purple-200'],
            'Stage' => ['label' => 'Stage', 'class' => 'bg-teal-50 text-teal-700 border-teal-200'],
            'Prestation' => ['label' => 'Prestation', 'class' => 'bg-amber-50 text-amber-700 border-amber-200'],
            default => ['label' => $this->contract_type ?? 'N/A', 'class' => 'bg-gray-50 text-gray-700 border-gray-200'],
        };
    }

    /**
     * Scope pour filtrer par statut actif.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'actif');
    }
}
