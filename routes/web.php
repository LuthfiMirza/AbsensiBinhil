<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DailyTaskController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Employee\MyAttendanceController;
use App\Http\Controllers\Employee\MyTaskController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\InventoryItemController;
use App\Http\Controllers\InventoryReportController;
use App\Http\Controllers\InventoryTransactionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TaskTemplateController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (! auth()->check()) {
        return redirect()->route('login');
    }

    return auth()->user()->isEmployee()
        ? redirect()->route('my-attendance.index')
        : redirect()->route('attendances.index');
});

Route::middleware('auth')->group(function () {
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('employee')->group(function () {
        Route::get('my-attendance', [MyAttendanceController::class, 'index'])->name('my-attendance.index');
        Route::post('my-attendance', [MyAttendanceController::class, 'store'])->name('my-attendance.store');
        Route::get('my-tasks', [MyTaskController::class, 'index'])->name('my-tasks.index');
        Route::post('my-tasks/{dailyTask}/start', [MyTaskController::class, 'start'])->name('my-tasks.start');
        Route::post('my-tasks/{dailyTask}/complete', [MyTaskController::class, 'complete'])->name('my-tasks.complete');
    });

    Route::middleware('admin')->group(function () {
        Route::get('dashboard', DashboardController::class)->name('dashboard');

        Route::resource('employees', EmployeeController::class);

        Route::resource('task-templates', TaskTemplateController::class)->except(['show']);
        Route::resource('daily-tasks', DailyTaskController::class)->except(['show']);
        Route::post('daily-tasks/{dailyTask}/mark-completed', [DailyTaskController::class, 'markCompleted'])
             ->name('daily-tasks.mark-completed');

        Route::resource('inventories', InventoryItemController::class)->except(['show']);
        Route::resource('inventory-transactions', InventoryTransactionController::class)->only(['index', 'create', 'store']);
        Route::get('inventory-reports/usage', [InventoryReportController::class, 'usage'])
             ->name('inventory-reports.usage');

        Route::resource('attendances', AttendanceController::class)->only([
            'index', 'create', 'store'
        ]);
        Route::post('attendances/absent', [AttendanceController::class, 'markAbsent'])
             ->name('attendances.absent');

        Route::get('reports/monthly/export', [ReportController::class, 'exportMonthly'])
             ->name('reports.monthly.export');
        Route::get('reports/monthly', [ReportController::class, 'monthly'])
             ->name('reports.monthly');
        Route::get('reports/monthly/{employee}/detail', [ReportController::class, 'monthlyDetail'])
             ->name('reports.monthly.detail');
    });
});

require __DIR__.'/auth.php';
