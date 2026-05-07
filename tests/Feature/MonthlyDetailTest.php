<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MonthlyDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_monthly_detail_shows_all_dates_statuses_missing_data_and_sundays(): void
    {
        $user = User::factory()->create();
        $employee = Employee::create([
            'name' => 'Petugas Detail',
            'employee_code' => 'PTG-001',
            'area' => 'Blok A',
            'shift' => 'pagi',
            'is_active' => true,
        ]);

        Attendance::create(['employee_id' => $employee->id, 'date' => '2026-05-04', 'check_in' => '06:00:00', 'check_out' => '14:00:00', 'status' => 'on_time', 'late_minutes' => 0]);
        Attendance::create(['employee_id' => $employee->id, 'date' => '2026-05-05', 'check_in' => '06:20:00', 'status' => 'late', 'late_minutes' => 20]);
        Attendance::create(['employee_id' => $employee->id, 'date' => '2026-05-06', 'status' => 'absent', 'late_minutes' => 0]);

        $response = $this->actingAs($user)->get(route('reports.monthly.detail', [
            'employee' => $employee,
            'month' => 5,
            'year' => 2026,
        ]));

        $response->assertOk();
        $response->assertSee('01 May 2026');
        $response->assertSee('31 May 2026');
        $response->assertSee('Tepat Waktu');
        $response->assertSee('Terlambat');
        $response->assertSee('Absent');
        $response->assertSee('Belum ada data');
        $response->assertSee('Libur');
        $response->assertSee('20 menit');
    }
}
