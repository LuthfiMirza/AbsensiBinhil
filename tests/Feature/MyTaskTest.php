<?php

namespace Tests\Feature;

use App\Models\DailyTask;
use App\Models\Employee;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MyTaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_employee_only_sees_own_tasks_and_can_complete_safely(): void
    {
        Carbon::setTestNow('2026-05-12 08:00:00');

        $employeeA = Employee::create(['name' => 'Petugas A', 'employee_code' => 'PTG-A', 'area' => 'Blok A', 'shift' => 'pagi', 'is_active' => true]);
        $employeeB = Employee::create(['name' => 'Petugas B', 'employee_code' => 'PTG-B', 'area' => 'Blok B', 'shift' => 'pagi', 'is_active' => true]);
        $userA = User::factory()->create(['role' => 'employee', 'employee_id' => $employeeA->id]);

        $ownTask = DailyTask::create(['employee_id' => $employeeA->id, 'task_date' => '2026-05-12', 'title' => 'Sapu lobby', 'area' => 'Lobby', 'status' => DailyTask::STATUS_PENDING]);
        $otherTask = DailyTask::create(['employee_id' => $employeeB->id, 'task_date' => '2026-05-12', 'title' => 'Bersihkan taman', 'area' => 'Taman', 'status' => DailyTask::STATUS_PENDING]);

        $this->actingAs($userA)->get(route('my-tasks.index'))
            ->assertOk()
            ->assertSee('Sapu lobby')
            ->assertDontSee('Bersihkan taman');

        $this->actingAs($userA)->post(route('my-tasks.start', $ownTask))->assertRedirect();
        $this->assertDatabaseHas('daily_tasks', ['id' => $ownTask->id, 'status' => DailyTask::STATUS_IN_PROGRESS]);

        $this->actingAs($userA)->post(route('my-tasks.complete', $ownTask), ['notes' => 'Sudah bersih.'])->assertRedirect();
        $ownTask->refresh();
        $this->assertSame(DailyTask::STATUS_COMPLETED, $ownTask->status);
        $this->assertSame($userA->id, $ownTask->completed_by);
        $this->assertNotNull($ownTask->completed_at);

        $completedAt = $ownTask->completed_at->copy();
        Carbon::setTestNow('2026-05-12 09:00:00');
        $this->actingAs($userA)->post(route('my-tasks.complete', $ownTask))->assertRedirect();
        $this->assertTrue($completedAt->equalTo($ownTask->fresh()->completed_at));

        $this->actingAs($userA)->post(route('my-tasks.start', $otherTask))->assertForbidden();

        Carbon::setTestNow();
    }
}
