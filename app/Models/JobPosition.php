<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPosition extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'department',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relation vers le personnel occupant ce poste.
     */
    public function employees()
    {
        return $this->hasMany(Employee::class, 'job_position_id');
    }

    /**
     * Scope pour filtrer les postes actifs.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
