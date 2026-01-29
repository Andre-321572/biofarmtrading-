<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    protected $fillable = ['first_name', 'last_name', 'position', 'shift'];

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
