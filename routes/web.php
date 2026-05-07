<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('attendances.index'));

Route::middleware('auth')->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Employees
    Route::resource('employees', EmployeeController::class);

    // Attendance
    Route::resource('attendances', AttendanceController::class)->only([
        'index', 'create', 'store'
    ]);
    Route::post('attendances/absent', [AttendanceController::class, 'markAbsent'])
         ->name('attendances.absent');

    // Reports
    Route::get('reports/monthly/export', [ReportController::class, 'exportMonthly'])
         ->name('reports.monthly.export');
    Route::get('reports/monthly', [ReportController::class, 'monthly'])
         ->name('reports.monthly');
    Route::get('reports/monthly/{employee}/detail', [ReportController::class, 'monthlyDetail'])
         ->name('reports.monthly.detail');
});

require __DIR__.'/auth.php';
