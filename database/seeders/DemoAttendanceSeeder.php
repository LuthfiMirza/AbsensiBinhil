<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoAttendanceSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@demo.test'],
            [
                'name' => 'Demo Admin',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );

        $employees = $this->seedEmployees();

        $this->seedCurrentMonthAttendances($employees);
        $this->seedPreviousMonthAttendances($employees);
    }

    private function seedEmployees(): array
    {
        $rows = [
            ['name' => 'Andi Saputra', 'employee_code' => 'PTG-001', 'area' => 'Blok A', 'shift' => 'pagi', 'phone' => '0812-1000-0001'],
            ['name' => 'Budi Santoso', 'employee_code' => 'PTG-002', 'area' => 'Blok A', 'shift' => 'pagi', 'phone' => '0812-1000-0002'],
            ['name' => 'Citra Dewi', 'employee_code' => 'PTG-003', 'area' => 'Blok B', 'shift' => 'siang', 'phone' => '0812-1000-0003'],
            ['name' => 'Deni Pratama', 'employee_code' => 'PTG-004', 'area' => 'Blok B', 'shift' => 'siang', 'phone' => '0812-1000-0004'],
            ['name' => 'Eka Lestari', 'employee_code' => 'PTG-005', 'area' => 'Blok C', 'shift' => 'pagi', 'phone' => '0812-1000-0005'],
            ['name' => 'Fajar Nugroho', 'employee_code' => 'PTG-006', 'area' => 'Lobby', 'shift' => 'pagi', 'phone' => '0812-1000-0006'],
            ['name' => 'Gina Permata', 'employee_code' => 'PTG-007', 'area' => 'Toilet Umum', 'shift' => 'siang', 'phone' => '0812-1000-0007'],
            ['name' => 'Hadi Wijaya', 'employee_code' => 'PTG-008', 'area' => 'Toilet Umum', 'shift' => 'sore', 'phone' => '0812-1000-0008'],
        ];

        $employees = [];

        foreach ($rows as $row) {
            $employees[$row['employee_code']] = Employee::query()->updateOrCreate(
                ['employee_code' => $row['employee_code']],
                array_merge($row, ['is_active' => true])
            );
        }

        return $employees;
    }

    private function seedCurrentMonthAttendances(array $employees): void
    {
        $monthStart = now()->startOfMonth();
        $lastDay = min(10, now()->day);
        $monthEnd = now()->copy()->day($lastDay);

        foreach (CarbonPeriod::create($monthStart, $monthEnd) as $date) {
            /** Minggu sengaja dilewati agar terlihat sebagai libur di detail dan tidak dihitung working day. */
            if ($date->isSunday()) {
                continue;
            }

            $day = $date->day;

            $this->upsertAttendance($employees['PTG-001'], $date, 'on_time', '07:50:00', '16:00:00', 0);
            $this->upsertAttendance($employees['PTG-002'], $date, $day % 3 === 0 ? 'late' : 'on_time', $day % 3 === 0 ? '08:14:00' : '07:58:00', '16:05:00', $day % 3 === 0 ? 14 : 0);
            $this->upsertAttendance($employees['PTG-003'], $date, 'on_time', '13:50:00', '22:00:00', 0);

            if ($day % 4 === 0) {
                $this->upsertAttendance($employees['PTG-004'], $date, 'absent', null, null, 0);
            } else {
                $this->upsertAttendance($employees['PTG-004'], $date, 'late', '14:18:00', '22:08:00', 18);
            }

            if ($day % 5 !== 0) {
                $this->upsertAttendance($employees['PTG-005'], $date, 'on_time', '07:55:00', '16:02:00', 0);
            }

            if ($day % 2 === 0) {
                $this->upsertAttendance($employees['PTG-006'], $date, 'late', '08:12:00', '16:10:00', 12);
            } else {
                $this->upsertAttendance($employees['PTG-006'], $date, 'on_time', '07:52:00', '16:00:00', 0);
            }

            if ($day % 3 !== 1) {
                $this->upsertAttendance($employees['PTG-007'], $date, 'on_time', '13:56:00', '22:02:00', 0);
            }

            if ($day % 6 === 0) {
                $this->upsertAttendance($employees['PTG-008'], $date, 'absent', null, null, 0);
            }
        }

        $sunday = $this->firstSundayOfMonth($monthStart);
        if ($sunday && $sunday->lte(now())) {
            /** Data Minggu ini sengaja dibuat untuk menguji laporan mengabaikan hari Minggu. */
            $this->upsertAttendance($employees['PTG-001'], $sunday, 'on_time', '07:50:00', '16:00:00', 0, 'Demo Sunday record; should be ignored by monthly score.');
        }
    }

    private function seedPreviousMonthAttendances(array $employees): void
    {
        $previousMonth = now()->subMonthNoOverflow()->startOfMonth();
        $dates = collect(CarbonPeriod::create($previousMonth, $previousMonth->copy()->addDays(9)))
            ->reject(fn (Carbon $date) => $date->isSunday())
            ->values();

        if ($dates->count() < 6) {
            return;
        }

        $this->upsertAttendance($employees['PTG-001'], $dates[0], 'on_time', '07:50:00', '16:00:00', 0);
        $this->upsertAttendance($employees['PTG-002'], $dates[1], 'on_time', '07:55:00', '16:00:00', 0);
        $this->upsertAttendance($employees['PTG-003'], $dates[2], 'on_time', '13:50:00', '22:00:00', 0);
        $this->upsertAttendance($employees['PTG-004'], $dates[3], 'late', '14:20:00', '22:08:00', 20);
        $this->upsertAttendance($employees['PTG-006'], $dates[4], 'late', '08:12:00', '16:04:00', 12);
        $this->upsertAttendance($employees['PTG-008'], $dates[5], 'absent', null, null, 0);
    }

    private function upsertAttendance(Employee $employee, Carbon $date, string $status, ?string $checkIn, ?string $checkOut, int $lateMinutes, ?string $notes = null): void
    {
        Attendance::query()->updateOrCreate(
            [
                'employee_id' => $employee->id,
                'date' => $date->toDateString(),
            ],
            [
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'status' => $status,
                'late_minutes' => $lateMinutes,
                'notes' => $notes,
            ]
        );
    }

    private function firstSundayOfMonth(Carbon $monthStart): ?Carbon
    {
        foreach (CarbonPeriod::create($monthStart->copy(), $monthStart->copy()->endOfMonth()) as $date) {
            if ($date->isSunday()) {
                return $date->copy();
            }
        }

        return null;
    }
}
