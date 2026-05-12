<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyTask extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_COMPLETED = 'completed';

    protected $fillable = [
        'task_template_id',
        'employee_id',
        'assigned_by',
        'completed_by',
        'task_date',
        'title',
        'description',
        'area',
        'shift',
        'status',
        'notes',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'task_date' => 'date',
            'completed_at' => 'datetime',
        ];
    }

    public function taskTemplate()
    {
        return $this->belongsTo(TaskTemplate::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function completedBy()
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_IN_PROGRESS => 'Sedang dikerjakan',
            self::STATUS_COMPLETED => 'Selesai',
            default => 'Belum dikerjakan',
        };
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            self::STATUS_IN_PROGRESS => 'status-late',
            self::STATUS_COMPLETED => 'status-on-time',
            default => 'status-empty',
        };
    }
}
