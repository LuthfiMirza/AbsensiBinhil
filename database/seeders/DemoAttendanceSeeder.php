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
                'role' => 'admin',
                'employee_id' => null,
            ]
        );

        $employees = $this->seedEmployees();

        $this->seedCurrentMonthAttendances($employees);
        $this->seedPreviousMonthAttendances($employees);
    }

    private function seedEmployees(): array
    {
        $rows = [
            ['name' => 'Andi Saputra', 'email' => 'andi@demo.test', 'employee_code' => 'PTG-001', 'area' => 'Blok A', 'shift' => 'pagi', 'phone' => '0812-1000-0001'],
            ['name' => 'Budi Santoso', 'email' => 'budi@demo.test', 'employee_code' => 'PTG-002', 'area' => 'Blok A', 'shift' => 'pagi', 'phone' => '0812-1000-0002'],
            ['name' => 'Citra Dewi', 'email' => 'citra@demo.test', 'employee_code' => 'PTG-003', 'area' => 'Blok B', 'shift' => 'siang', 'phone' => '0812-1000-0003'],
            ['name' => 'Deni Pratama', 'email' => 'deni@demo.test', 'employee_code' => 'PTG-004', 'area' => 'Blok B', 'shift' => 'siang', 'phone' => '0812-1000-0004'],
            ['name' => 'Eka Lestari', 'email' => 'eka@demo.test', 'employee_code' => 'PTG-005', 'area' => 'Blok C', 'shift' => 'pagi', 'phone' => '0812-1000-0005'],
            ['name' => 'Fajar Nugroho', 'email' => 'fajar@demo.test', 'employee_code' => 'PTG-006', 'area' => 'Lobby', 'shift' => 'pagi', 'phone' => '0812-1000-0006'],
            ['name' => 'Gina Permata', 'email' => 'gina@demo.test', 'employee_code' => 'PTG-007', 'area' => 'Toilet Umum', 'shift' => 'siang', 'phone' => '0812-1000-0007'],
            ['name' => 'Hadi Wijaya', 'email' => 'hadi@demo.test', 'employee_code' => 'PTG-008', 'area' => 'Toilet Umum', 'shift' => 'sore', 'phone' => '0812-1000-0008'],
            ['name' => 'Indra Maulana', 'email' => 'indra@demo.test', 'employee_code' => 'PTG-009', 'area' => 'Blok D', 'shift' => 'pagi', 'phone' => '0812-1000-0009'],
            ['name' => 'Joko Prasetyo', 'email' => 'joko@demo.test', 'employee_code' => 'PTG-010', 'area' => 'Blok D', 'shift' => 'siang', 'phone' => '0812-1000-0010'],
            ['name' => 'Kartika Sari', 'email' => 'kartika@demo.test', 'employee_code' => 'PTG-011', 'area' => 'Taman', 'shift' => 'pagi', 'phone' => '0812-1000-0011'],
            ['name' => 'Lukman Hakim', 'email' => 'lukman@demo.test', 'employee_code' => 'PTG-012', 'area' => 'Taman', 'shift' => 'sore', 'phone' => '0812-1000-0012'],
            ['name' => 'Maya Putri', 'email' => 'maya@demo.test', 'employee_code' => 'PTG-013', 'area' => 'Pos Utama', 'shift' => 'pagi', 'phone' => '0812-1000-0013'],
            ['name' => 'Nanda Firmansyah', 'email' => 'nanda@demo.test', 'employee_code' => 'PTG-014', 'area' => 'Pos Utama', 'shift' => 'siang', 'phone' => '0812-1000-0014'],
            ['name' => 'Oki Ramadhan', 'email' => 'oki@demo.test', 'employee_code' => 'PTG-015', 'area' => 'Kolam', 'shift' => 'pagi', 'phone' => '0812-1000-0015'],
            ['name' => 'Putu Wijaya', 'email' => 'putu@demo.test', 'employee_code' => 'PTG-016', 'area' => 'Kolam', 'shift' => 'sore', 'phone' => '0812-1000-0016'],
            ['name' => 'Qori Ananda', 'email' => 'qori@demo.test', 'employee_code' => 'PTG-017', 'area' => 'Jalan Utama', 'shift' => 'pagi', 'phone' => '0812-1000-0017'],
            ['name' => 'Rini Handayani', 'email' => 'rini@demo.test', 'employee_code' => 'PTG-018', 'area' => 'Jalan Utama', 'shift' => 'siang', 'phone' => '0812-1000-0018'],
            ['name' => 'Surya Darma', 'email' => 'surya@demo.test', 'employee_code' => 'PTG-019', 'area' => 'TPS', 'shift' => 'pagi', 'phone' => '0812-1000-0019'],
            ['name' => 'Tari Wulandari', 'email' => 'tari@demo.test', 'employee_code' => 'PTG-020', 'area' => 'TPS', 'shift' => 'sore', 'phone' => '0812-1000-0020'],
        ];

        $employees = [];

        foreach ($rows as $row) {
            $employees[$row['employee_code']] = Employee::query()->updateOrCreate(
                ['employee_code' => $row['employee_code']],
                array_merge(collect($row)->except('email')->all(), ['is_active' => true])
            );

            foreach ([$row['email'], strtolower($row['employee_code']).'@demo.test'] as $email) {
                User::query()->updateOrCreate(
                    ['email' => $email],
                    [
                        'name' => $row['name'],
                        'password' => Hash::make('password123'),
                        'email_verified_at' => now(),
                        'role' => 'employee',
                        'employee_id' => $employees[$row['employee_code']]->id,
                    ]
                );
            }
        }

        return $employees;
    }

    private function seedCurrentMonthAttendances(array $employees): void
    {
        $monthStart = now()->startOfMonth();
        $lastDay = min(14, now()->day);
        $monthEnd = now()->copy()->day($lastDay);
        $employeeList = array_values($employees);
        $statuses = ['on_time', 'late', 'permission', 'sick', 'alpha', 'holiday'];

        foreach (CarbonPeriod::create($monthStart, $monthEnd) as $date) {
            if ($date->isSunday()) {
                continue;
            }

            foreach ($employeeList as $index => $employee) {
                $status = $statuses[($date->day + $index) % count($statuses)];
                $this->upsertStatusForShift($employee, $date, $status);
            }
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

    private function upsertStatusForShift(Employee $employee, Carbon $date, string $status): void
    {
        if ($status === 'on_time') {
            $this->upsertAttendance($employee, $date, $status, $employee->shift === 'siang' ? '13:55:00' : ($employee->shift === 'sore' ? '14:55:00' : '07:55:00'), null, 0);
            return;
        }

        if ($status === 'late') {
            $this->upsertAttendance($employee, $date, $status, $employee->shift === 'siang' ? '14:20:00' : ($employee->shift === 'sore' ? '15:20:00' : '08:20:00'), null, 20);
            return;
        }

        $notes = match ($status) {
            'permission' => 'Demo: izin keluarga',
            'sick' => 'Demo: sakit',
            'alpha' => 'Demo: alfa tanpa keterangan',
            'holiday' => 'Demo: libur area',
            default => null,
        };

        $this->upsertAttendance($employee, $date, $status, null, null, 0, $notes);
    }

    private function upsertAttendance(Employee $employee, Carbon $date, string $status, ?string $checkIn, ?string $checkOut, int $lateMinutes, ?string $notes = null): void
    {
        $attendance = Attendance::query()
            ->where('employee_id', $employee->id)
            ->whereDate('date', $date->toDateString())
            ->first();

        $values = [
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'status' => $status,
            'late_minutes' => $lateMinutes,
            'notes' => $notes,
        ];

        if ($attendance) {
            $attendance->update($values);
            return;
        }

        Attendance::query()->create(array_merge($values, [
            'employee_id' => $employee->id,
            'date' => $date->toDateString(),
        ]));
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
