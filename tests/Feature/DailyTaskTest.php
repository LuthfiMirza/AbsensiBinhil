<?php

namespace Tests\Feature;

use App\Models\DailyTask;
use App\Models\Employee;
use App\Models\TaskTemplate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DailyTaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_daily_task_from_template_and_filter_it(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $employee = Employee::create(['name' => 'Petugas A', 'employee_code' => 'PTG-A', 'area' => 'Blok A', 'shift' => 'pagi', 'is_active' => true]);
        $template = TaskTemplate::create(['name' => 'Buang sampah', 'default_area' => 'TPS', 'default_shift' => 'pagi', 'is_active' => true]);

        $this->actingAs($admin)->post(route('daily-tasks.store'), [
            'task_date' => '2026-05-12',
            'employee_id' => $employee->id,
            'task_template_id' => $template->id,
            'status' => DailyTask::STATUS_PENDING,
        ])->assertRedirect(route('daily-tasks.index'));

        $this->assertDatabaseHas('daily_tasks', [
            'employee_id' => $employee->id,
            'task_template_id' => $template->id,
            'title' => 'Buang sampah',
            'area' => 'TPS',
            'status' => DailyTask::STATUS_PENDING,
        ]);

        $this->actingAs($admin)->get(route('daily-tasks.index', ['task_date' => '2026-05-12', 'status' => DailyTask::STATUS_PENDING]))
            ->assertOk()
            ->assertSee('Buang sampah')
            ->assertSee('Petugas A');
    }

    public function test_employee_cannot_access_admin_daily_tasks(): void
    {
        $employeeUser = User::factory()->create(['role' => 'employee']);

        $this->actingAs($employeeUser)->get(route('daily-tasks.index'))->assertForbidden();
    }
}
