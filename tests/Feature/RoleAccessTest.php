<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_admin_pages_and_is_redirected_after_login(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin-role@test.local',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $this->post('/login', [
            'email' => $admin->email,
            'password' => 'password123',
        ])->assertRedirect(route('dashboard', absolute: false));

        $this->actingAs($admin)->get('/attendances')->assertOk();
        $this->actingAs($admin)->get('/reports/monthly')->assertOk();
    }

    public function test_employee_login_redirects_to_my_attendance_and_cannot_access_admin_pages(): void
    {
        $employee = Employee::create([
            'name' => 'Petugas Role',
            'employee_code' => 'ROLE-001',
            'area' => 'Blok A',
            'shift' => 'pagi',
            'is_active' => true,
        ]);
        $user = User::factory()->create([
            'email' => 'role-001@demo.test',
            'password' => Hash::make('password123'),
            'role' => 'employee',
            'employee_id' => $employee->id,
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ])->assertRedirect(route('my-attendance.index', absolute: false));

        $this->actingAs($user)->get('/my-attendance')->assertOk()->assertSee('Petugas Role');
        $this->actingAs($user)->get('/attendances')->assertForbidden();
        $this->actingAs($user)->get('/reports/monthly')->assertForbidden();
    }

    public function test_employee_check_in_uses_own_employee_id_and_ignores_posted_employee_id(): void
    {
        $ownEmployee = Employee::create([
            'name' => 'Petugas Sendiri',
            'employee_code' => 'OWN-001',
            'area' => 'Blok A',
            'shift' => 'pagi',
            'is_active' => true,
        ]);
        $otherEmployee = Employee::create([
            'name' => 'Petugas Lain',
            'employee_code' => 'OTHER-001',
            'area' => 'Blok B',
            'shift' => 'pagi',
            'is_active' => true,
        ]);
        $user = User::factory()->create([
            'role' => 'employee',
            'employee_id' => $ownEmployee->id,
        ]);

        $this->actingAs($user)->post('/my-attendance', [
            'type' => 'check_in',
            'employee_id' => $otherEmployee->id,
        ])->assertSessionHasNoErrors();

        $this->assertTrue(Attendance::where('employee_id', $ownEmployee->id)->whereDate('date', today())->exists());
        $this->assertFalse(Attendance::where('employee_id', $otherEmployee->id)->whereDate('date', today())->exists());
    }

    public function test_seeders_create_admin_and_employee_accounts_without_duplicates(): void
    {
        $this->seed(\Database\Seeders\DemoAttendanceSeeder::class);
        $this->seed(\Database\Seeders\DemoAttendanceSeeder::class);

        $this->assertSame(1, User::where('email', 'admin@demo.test')->where('role', 'admin')->count());
        $this->assertSame(16, User::where('role', 'employee')->whereNotNull('employee_id')->count());
        $this->assertSame(1, User::where('email', 'andi@demo.test')->where('role', 'employee')->count());
        $this->assertSame(1, User::where('email', 'ptg-001@demo.test')->where('role', 'employee')->count());
    }
}
