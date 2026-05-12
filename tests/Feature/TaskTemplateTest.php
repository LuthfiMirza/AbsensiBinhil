<?php

namespace Tests\Feature;

use App\Models\TaskTemplate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTemplateTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_manage_task_templates(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->get(route('task-templates.index'))
            ->assertOk()
            ->assertSee('Master Tugas');

        $this->actingAs($admin)->post(route('task-templates.store'), [
            'name' => 'Sapu area lobby',
            'description' => 'Bersihkan area lobby utama.',
            'default_area' => 'Lobby',
            'default_shift' => 'pagi',
            'sort_order' => 1,
            'is_active' => 1,
        ])->assertRedirect(route('task-templates.index'));

        $template = TaskTemplate::first();
        $this->assertSame('Sapu area lobby', $template->name);

        $this->actingAs($admin)->put(route('task-templates.update', $template), [
            'name' => 'Sapu lobby utama',
            'description' => 'Update instruksi.',
            'default_area' => 'Lobby',
            'default_shift' => 'pagi',
            'sort_order' => 2,
            'is_active' => 1,
        ])->assertRedirect(route('task-templates.index'));

        $this->assertDatabaseHas('task_templates', ['id' => $template->id, 'name' => 'Sapu lobby utama', 'sort_order' => 2]);
    }

    public function test_employee_cannot_access_task_template_admin_pages(): void
    {
        $employeeUser = User::factory()->create(['role' => 'employee']);

        $this->actingAs($employeeUser)->get(route('task-templates.index'))->assertForbidden();
    }
}
