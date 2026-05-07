<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkSchedule extends Model
{
    protected $fillable = [
        'shift_name', 'start_time', 'end_time', 'late_tolerance'
    ];
}