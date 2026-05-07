<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MonthlyExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_monthly_export_csv_uses_filters_and_expected_columns(): void
    {
        $user = User::factory()->create();
        $blokA = Employee::create([
            'name' => 'Petugas CSV A',
            'employee_code' => 'PTG-001',
            'area' => 'Blok A',
            'shift' => 'pagi',
            'is_active' => true,
        ]);
        $blokB = Employee::create([
            'name' => 'Petugas CSV B',
            'employee_code' => 'PTG-002',
            'area' => 'Blok B',
            'shift' => 'pagi',
            'is_active' => true,
        ]);

        Attendance::create(['employee_id' => $blokA->id, 'date' => '2026-05-04', 'check_in' => '06:00:00', 'status' => 'on_time', 'late_minutes' => 0]);
        Attendance::create(['employee_id' => $blokB->id, 'date' => '2026-05-04', 'check_in' => '06:00:00', 'status' => 'on_time', 'late_minutes' => 0]);

        $response = $this->actingAs($user)->get('/reports/monthly/export?month=5&year=2026&area=Blok%20A');

        $response->assertOk();
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
        $csv = $response->streamedContent();

        $this->assertStringContainsString('Nama petugas', $csv);
        $this->assertStringContainsString('Area/blok', $csv);
        $this->assertStringContainsString('Total hadir', $csv);
        $this->assertStringContainsString('Skor performa', $csv);
        $this->assertStringContainsString('Petugas CSV A', $csv);
        $this->assertStringNotContainsString('Petugas CSV B', $csv);
    }
}
