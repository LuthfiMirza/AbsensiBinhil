<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_shows_today_counters_and_inventory_placeholder(): void
    {
        Carbon::setTestNow('2026-05-08 09:00:00');
        $admin = User::factory()->create(['role' => 'admin']);

        foreach (['A', 'B', 'C', 'D', 'E', 'F'] as $code) {
            Employee::create([
                'name' => 'Petugas '.$code,
                'employee_code' => 'PTG-'.$code,
                'area' => 'Blok '.$code,
                'shift' => 'pagi',
                'is_active' => true,
            ]);
        }

        $employees = Employee::all()->values();
        foreach (['on_time', 'late', 'permission', 'sick', 'alpha'] as $index => $status) {
            Attendance::create([
                'employee_id' => $employees[$index]->id,
                'date' => '2026-05-08',
                'status' => $status,
                'late_minutes' => $status === 'late' ? 12 : 0,
            ]);
        }

        $response = $this->actingAs($admin)->get('/dashboard');

        $response->assertOk();
        $response->assertSee('Dashboard Koordinator');
        $response->assertSee('Stok Barang Rendah');
        $response->assertSee('Semua stok aman');
        $response->assertSee('>6<', false);
        $response->assertSee('>2<', false);
        $response->assertSee('>1<', false);

        Carbon::setTestNow();
    }
}
