<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::where('is_active', true)->latest()->get();
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
        ]);

        Employee::create($request->all());

        return redirect()->route('employees.index')
                         ->with('success', 'Petugas berhasil ditambahkan!');
    }

    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'area'  => 'required',
            'shift' => 'required|in:pagi,siang,sore',
        ]);

        $employee->update($request->all());

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