<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with('user')->where('is_active', true)->latest()->get();
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'employee_code' => 'required|unique:employees',
            'area'          => 'required',
            'shift'         => 'required|in:pagi,siang,sore',
            'phone'         => 'nullable|string|max:20',
            'email'         => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password'      => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        DB::transaction(function () use ($request) {
            $employee = Employee::create($request->only([
                'name', 'employee_code', 'area', 'shift', 'phone'
            ]));

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'employee',
                'employee_id' => $employee->id,
            ]);
        });

        return redirect()->route('employees.index')
                         ->with('success', 'Petugas dan akun login berhasil ditambahkan!');
    }

    public function edit(Employee $employee)
    {
        $employee->load('user');

        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'area'  => 'required',
            'shift' => 'required|in:pagi,siang,sore',
            'phone' => 'nullable|string|max:20',
            'email' => [
                $employee->user ? 'nullable' : 'required_with:password',
                'string', 'lowercase', 'email', 'max:255',
                Rule::unique('users', 'email')->ignore($employee->user?->id),
            ],
            'password' => [
                $employee->user ? 'nullable' : 'required_with:email',
                'confirmed', Rules\Password::defaults(),
            ],
        ]);

        DB::transaction(function () use ($request, $employee) {
            $employee->update($request->only(['name', 'area', 'shift', 'phone']));

            if ($request->filled('email') || $request->filled('password')) {
                $user = $employee->user ?: new User([
                    'role' => 'employee',
                    'employee_id' => $employee->id,
                ]);

                $user->name = $request->name;
                $user->email = $request->email ?: $user->email;
                $user->role = 'employee';
                $user->employee_id = $employee->id;

                if ($request->filled('password')) {
                    $user->password = Hash::make($request->password);
                }

                $user->save();
            } elseif ($employee->user) {
                $employee->user->update(['name' => $request->name]);
            }
        });

        return redirect()->route('employees.index')
                         ->with('success', 'Data petugas diperbarui!');
    }

    public function destroy(Employee $employee)
    {
        $employee->update(['is_active' => false]); // soft delete
        return redirect()->route('employees.index')
                         ->with('success', 'Petugas dinonaktifkan.');
    }
}
