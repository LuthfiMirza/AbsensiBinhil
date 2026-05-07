<?php

namespace Database\Seeders;

use App\Models\WorkSchedule;
use Illuminate\Database\Seeder;

class WorkScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $schedules = [
            ['shift_name' => 'pagi',  'start_time' => '06:00', 'end_time' => '14:00', 'late_tolerance' => 15],
            ['shift_name' => 'siang', 'start_time' => '14:00', 'end_time' => '22:00', 'late_tolerance' => 15],
            ['shift_name' => 'sore',  'start_time' => '22:00', 'end_time' => '06:00', 'late_tolerance' => 15],
        ];

        foreach ($schedules as $schedule) {
            WorkSchedule::query()->updateOrCreate(
                ['shift_name' => $schedule['shift_name']],
                $schedule
            );
        }
    }
}
