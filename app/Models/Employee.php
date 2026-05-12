<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'name', 'employee_code', 'phone', 'area', 'shift', 'is_active'
    ];

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function dailyTasks()
    {
        return $this->hasMany(DailyTask::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function workSchedule()
    {
        return WorkSchedule::where('shift_name', $this->shift)->first();
    }
}
