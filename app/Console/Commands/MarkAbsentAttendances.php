<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MarkAbsentAttendances extends Command
{
    protected $signature = 'attendance:mark-absent {--date= : Tanggal absensi format Y-m-d}';

    protected $description = 'Mark active employees without attendance as absent for a given date.';

    public function handle(): int
    {
        $date = $this->option('date')
            ? Carbon::parse($this->option('date'))->toDateString()
            : now()->subDay()->toDateString();

        if (Carbon::parse($date)->isSunday()) {
            $this->info("Auto absent date: {$date}");
            $this->info('Skipped because Sunday is not a working day.');

            return self::SUCCESS;
        }

        $created = 0;
        $skipped = 0;

        DB::transaction(function () use ($date, &$created, &$skipped) {
            Employee::query()
                ->where('is_active', true)
                ->orderBy('id')
                ->chunkById(100, function ($employees) use ($date, &$created, &$skipped) {
                    $employeeIds = $employees->pluck('id');
                    $existingEmployeeIds = Attendance::query()
                        ->whereDate('date', $date)
                        ->whereIn('employee_id', $employeeIds)
                        ->pluck('employee_id')
                        ->all();

                    foreach ($employees as $employee) {
                        if (in_array($employee->id, $existingEmployeeIds, true)) {
                            $skipped++;
                            continue;
                        }

                        Attendance::query()->create([
                            'employee_id' => $employee->id,
                            'date' => $date,
                            'check_in' => null,
                            'check_out' => null,
                            'status' => 'absent',
                            'late_minutes' => 0,
                        ]);

                        $created++;
                    }
                });
        });

        $this->info("Auto absent date: {$date}");
        $this->info("Created absent records: {$created}");
        $this->info("Skipped existing records: {$skipped}");

        return self::SUCCESS;
    }
}
