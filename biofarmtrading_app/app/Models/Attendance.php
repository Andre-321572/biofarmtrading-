<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = ['worker_id', 'date', 'session', 'status', 'arrival_time', 'departure_time'];

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }
}
