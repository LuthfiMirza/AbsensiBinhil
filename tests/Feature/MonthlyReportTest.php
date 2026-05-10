<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MonthlyReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_monthly_report_counts_statuses_filters_area_and_uses_dynamic_years(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $blokA = Employee::create([
            'name' => 'Petugas Blok A',
            'employee_code' => 'PTG-001',
            'area' => 'Blok A',
            'shift' => 'pagi',
            'is_active' => true,
        ]);
        $blokB = Employee::create([
            'name' => 'Petugas Blok B',
            'employee_code' => 'PTG-002',
            'area' => 'Blok B',
            'shift' => 'pagi',
            'is_active' => true,
        ]);

        Attendance::create(['employee_id' => $blokA->id, 'date' => '2026-05-04', 'check_in' => '06:00:00', 'status' => 'on_time', 'late_minutes' => 0]);
        Attendance::create(['employee_id' => $blokA->id, 'date' => '2026-05-05', 'check_in' => '06:30:00', 'status' => 'late', 'late_minutes' => 30]);
        Attendance::create(['employee_id' => $blokA->id, 'date' => '2026-05-06', 'status' => 'absent', 'late_minutes' => 0]);
        Attendance::create(['employee_id' => $blokA->id, 'date' => '2026-05-07', 'status' => 'permission', 'late_minutes' => 0]);
        Attendance::create(['employee_id' => $blokA->id, 'date' => '2026-05-08', 'status' => 'sick', 'late_minutes' => 0]);
        Attendance::create(['employee_id' => $blokA->id, 'date' => '2026-05-09', 'status' => 'holiday', 'late_minutes' => 0]);
        Attendance::create(['employee_id' => $blokB->id, 'date' => '2026-05-04', 'check_in' => '06:00:00', 'status' => 'on_time', 'late_minutes' => 0]);

        $response = $this->actingAs($user)->get('/reports/monthly?month=5&year=2026&area=Blok%20A');

        $response->assertOk();
        $response->assertSee('Petugas Blok A');
        $response->assertDontSee('Petugas Blok B');
        $response->assertSee('2026');
        $response->assertSee('26 hari kerja');
        $response->assertSee('>2<', false);
        $response->assertSee('>1<', false);
        $response->assertSee('30 menit');
        $response->assertSee('Izin');
        $response->assertSee('Sakit');
        $response->assertSee('Alfa');
        $response->assertSee('Libur');
    }

    public function test_monthly_report_handles_employee_without_attendance_and_ignores_sunday_records(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $employee = Employee::create([
            'name' => 'Petugas Kosong',
            'employee_code' => 'PTG-001',
            'area' => 'Blok A',
            'shift' => 'pagi',
            'is_active' => true,
        ]);

        Attendance::create([
            'employee_id' => $employee->id,
            'date' => '2026-05-03',
            'status' => 'absent',
            'late_minutes' => 0,
        ]);

        $response = $this->actingAs($user)->get('/reports/monthly?month=5&year=2026');

        $response->assertOk();
        $response->assertSee('Petugas Kosong');
        $response->assertSee('26 hari kerja');
        $response->assertSee('>0<', false);
    }
}
