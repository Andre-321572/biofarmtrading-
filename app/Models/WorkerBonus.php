<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkerBonus extends Model
{
    protected $fillable = [
        'worker_id',
        'month',
        'amount',
        'reason',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'integer',
    ];

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
