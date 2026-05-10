<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_record_new_absence_statuses(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $employee = Employee::create([
            'name' => 'Petugas Status',
            'employee_code' => 'STS-001',
            'area' => 'Blok A',
            'shift' => 'pagi',
            'is_active' => true,
        ]);

        foreach (['permission', 'sick', 'alpha', 'holiday'] as $index => $status) {
            $date = '2026-05-'.str_pad((string) (4 + $index), 2, '0', STR_PAD_LEFT);

            $this->actingAs($admin)->post(route('attendances.absent'), [
                'employee_id' => $employee->id,
                'date' => $date,
                'status' => $status,
                'notes' => 'Test '.$status,
            ])->assertRedirect(route('attendances.index'));

            $this->assertTrue(Attendance::query()
                ->where('employee_id', $employee->id)
                ->whereDate('date', $date)
                ->where('status', $status)
                ->whereNull('check_in')
                ->whereNull('check_out')
                ->where('late_minutes', 0)
                ->exists());
        }
    }
}
