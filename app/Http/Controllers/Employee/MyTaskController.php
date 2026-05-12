<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\DailyTask;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MyTaskController extends Controller
{
    public function index(Request $request): View
    {
        $employee = $request->user()->employee;
        abort_unless($employee, 403);

        $today = Carbon::today()->toDateString();
        $todayTasks = DailyTask::query()
            ->where('employee_id', $employee->id)
            ->whereDate('task_date', $today)
            ->orderByRaw("CASE status WHEN 'pending' THEN 1 WHEN 'in_progress' THEN 2 ELSE 3 END")
            ->orderBy('created_at')
            ->get();

        $summary = [
            'total' => $todayTasks->count(),
            'completed' => $todayTasks->where('status', DailyTask::STATUS_COMPLETED)->count(),
            'in_progress' => $todayTasks->where('status', DailyTask::STATUS_IN_PROGRESS)->count(),
            'pending' => $todayTasks->where('status', DailyTask::STATUS_PENDING)->count(),
        ];

        $recentTasks = DailyTask::query()
            ->where('employee_id', $employee->id)
            ->whereDate('task_date', '<', $today)
            ->latest('task_date')
            ->latest()
            ->limit(10)
            ->get();

        return view('employee.my-tasks', compact('employee', 'today', 'todayTasks', 'summary', 'recentTasks'));
    }

    public function start(Request $request, DailyTask $dailyTask): RedirectResponse
    {
        $this->authorizeEmployeeTask($request, $dailyTask);

        if ($dailyTask->status === DailyTask::STATUS_PENDING) {
            $dailyTask->update(['status' => DailyTask::STATUS_IN_PROGRESS]);
        }

        return back()->with('success', $dailyTask->isCompleted() ? 'Tugas ini sudah selesai.' : 'Tugas mulai dikerjakan.');
    }

    public function complete(Request $request, DailyTask $dailyTask): RedirectResponse
    {
        $this->authorizeEmployeeTask($request, $dailyTask);

        $data = $request->validate(['notes' => ['nullable', 'string']]);

        if (! $dailyTask->isCompleted()) {
            $dailyTask->update([
                'status' => DailyTask::STATUS_COMPLETED,
                'notes' => $data['notes'] ?? $dailyTask->notes,
                'completed_at' => now(),
                'completed_by' => $request->user()->id,
            ]);

            return back()->with('success', 'Tugas berhasil ditandai selesai.');
        }

        return back()->with('success', 'Tugas ini sudah selesai sebelumnya.');
    }

    private function authorizeEmployeeTask(Request $request, DailyTask $dailyTask): void
    {
        abort_unless($request->user()->employee_id && $dailyTask->employee_id === $request->user()->employee_id, 403);
    }
}
