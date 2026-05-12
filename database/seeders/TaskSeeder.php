<?php

namespace Database\Seeders;

use App\Models\DailyTask;
use App\Models\Employee;
use App\Models\TaskTemplate;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $templates = collect([
            ['name' => 'Sapu area lobby', 'default_area' => 'Lobby', 'default_shift' => 'pagi'],
            ['name' => 'Buang sampah', 'default_area' => 'Area Umum', 'default_shift' => 'pagi'],
            ['name' => 'Pel lantai koridor', 'default_area' => 'Koridor', 'default_shift' => 'pagi'],
            ['name' => 'Cek toilet umum', 'default_area' => 'Toilet Umum', 'default_shift' => 'siang'],
            ['name' => 'Bersihkan taman', 'default_area' => 'Taman', 'default_shift' => 'pagi'],
            ['name' => 'Cek saluran air', 'default_area' => 'Drainase', 'default_shift' => 'siang'],
            ['name' => 'Lap kaca pos security', 'default_area' => 'Pos Security', 'default_shift' => 'pagi'],
            ['name' => 'Bersihkan area parkir', 'default_area' => 'Parkir', 'default_shift' => 'siang'],
            ['name' => 'Ganti plastik tempat sampah', 'default_area' => 'Area Umum', 'default_shift' => 'pagi'],
            ['name' => 'Semprot cairan pembersih area umum', 'default_area' => 'Area Umum', 'default_shift' => 'siang'],
            ['name' => 'Rapikan gudang alat', 'default_area' => 'Gudang', 'default_shift' => 'sore'],
            ['name' => 'Cek stok sabun toilet', 'default_area' => 'Toilet Umum', 'default_shift' => 'siang'],
        ])->map(function (array $data, int $index) {
            return TaskTemplate::query()->updateOrCreate(
                ['name' => $data['name']],
                $data + [
                    'description' => 'Kerjakan sesuai standar kebersihan Bintaro Hill dan laporkan jika ada kendala.',
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ]
            );
        });

        $admin = User::query()->where('role', 'admin')->first();
        $employees = Employee::query()->where('is_active', true)->orderBy('employee_code')->take(4)->get();

        if (! $admin || $employees->isEmpty()) {
            return;
        }

        $statuses = [DailyTask::STATUS_PENDING, DailyTask::STATUS_IN_PROGRESS, DailyTask::STATUS_COMPLETED];
        $dates = [today()->subDays(2), today()->subDay(), today()];

        foreach ($employees as $employeeIndex => $employee) {
            foreach ($dates as $dateIndex => $date) {
                foreach ($templates->slice($employeeIndex * 2, 3)->values() as $taskIndex => $template) {
                    $status = $date->isToday()
                        ? $statuses[($employeeIndex + $taskIndex) % count($statuses)]
                        : DailyTask::STATUS_COMPLETED;

                    DailyTask::query()->updateOrCreate(
                        [
                            'employee_id' => $employee->id,
                            'task_date' => $date->toDateString(),
                            'task_template_id' => $template->id,
                        ],
                        [
                            'assigned_by' => $admin->id,
                            'completed_by' => $status === DailyTask::STATUS_COMPLETED ? $admin->id : null,
                            'title' => $template->name,
                            'description' => $template->description,
                            'area' => $template->default_area ?: $employee->area,
                            'shift' => $template->default_shift ?: $employee->shift,
                            'status' => $status,
                            'notes' => $status === DailyTask::STATUS_COMPLETED ? 'Selesai sesuai jadwal.' : null,
                            'completed_at' => $status === DailyTask::STATUS_COMPLETED ? $date->copy()->setTime(10 + $taskIndex, 0) : null,
                        ]
                    );
                }
            }
        }
    }
}
