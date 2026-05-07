<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceAutoAbsentCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_creates_absent_for_employee_without_attendance(): void
    {
        $employee = Employee::create([
            'name' => 'Petugas A',
            'employee_code' => 'PTG-001',
            'area' => 'Blok A',
            'shift' => 'pagi',
            'is_active' => true,
        ]);

        $this->artisan('attendance:mark-absent', ['--date' => '2026-05-07'])
            ->expectsOutput('Created absent records: 1')
            ->expectsOutput('Skipped existing records: 0')
            ->assertExitCode(0);

        $this->assertTrue(Attendance::where('employee_id', $employee->id)
            ->whereDate('date', '2026-05-07')
            ->where('status', 'absent')
            ->where('late_minutes', 0)
            ->exists());
    }

    public function test_command_does_not_duplicate_existing_attendance(): void
    {
        $employee = Employee::create([
            'name' => 'Petugas A',
            'employee_code' => 'PTG-001',
            'area' => 'Blok A',
            'shift' => 'pagi',
            'is_active' => true,
        ]);

        Attendance::create([
            'employee_id' => $employee->id,
            'date' => '2026-05-07',
            'check_in' => '06:00:00',
            'status' => 'on_time',
            'late_minutes' => 0,
        ]);

        $this->artisan('attendance:mark-absent', ['--date' => '2026-05-07'])
            ->expectsOutput('Created absent records: 0')
            ->expectsOutput('Skipped existing records: 1')
            ->assertExitCode(0);

        $this->assertSame(1, Attendance::where('employee_id', $employee->id)->whereDate('date', '2026-05-07')->count());
    }

    public function test_command_is_idempotent_and_only_creates_missing_records(): void
    {
        $employeeA = Employee::create([
            'name' => 'Petugas A',
            'employee_code' => 'PTG-001',
            'area' => 'Blok A',
            'shift' => 'pagi',
            'is_active' => true,
        ]);
        $employeeB = Employee::create([
            'name' => 'Petugas B',
            'employee_code' => 'PTG-002',
            'area' => 'Blok B',
            'shift' => 'siang',
            'is_active' => true,
        ]);

        Attendance::create([
            'employee_id' => $employeeA->id,
            'date' => '2026-05-07',
            'check_in' => '06:00:00',
            'status' => 'on_time',
            'late_minutes' => 0,
        ]);

        $this->artisan('attendance:mark-absent', ['--date' => '2026-05-07'])->assertExitCode(0);
        $this->artisan('attendance:mark-absent', ['--date' => '2026-05-07'])->assertExitCode(0);

        $this->assertSame(2, Attendance::whereDate('date', '2026-05-07')->count());
        $this->assertTrue(Attendance::where('employee_id', $employeeB->id)
            ->whereDate('date', '2026-05-07')
            ->where('status', 'absent')
            ->exists());
    }

    public function test_command_skips_sunday_because_it_is_not_a_working_day(): void
    {
        Employee::create([
            'name' => 'Petugas A',
            'employee_code' => 'PTG-001',
            'area' => 'Blok A',
            'shift' => 'pagi',
            'is_active' => true,
        ]);

        $this->artisan('attendance:mark-absent', ['--date' => '2026-05-03'])
            ->expectsOutput('Skipped because Sunday is not a working day.')
            ->assertExitCode(0);

        $this->assertSame(0, Attendance::count());
    }
}
