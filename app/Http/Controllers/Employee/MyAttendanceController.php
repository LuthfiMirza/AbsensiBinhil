<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\WorkSchedule;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MyAttendanceController extends Controller
{
    public function index(Request $request): View
    {
        $employee = $request->user()->employee;
        $today = Carbon::today()->toDateString();
        $isHoliday = Carbon::parse($today)->isSunday();
        $attendance = Attendance::query()
            ->where('employee_id', $employee->id)
            ->whereDate('date', $today)
            ->first();

        $history = Attendance::query()
            ->where('employee_id', $employee->id)
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->latest('date')
            ->limit(15)
            ->get();

        return view('employee.my-attendance', compact('employee', 'today', 'attendance', 'history', 'isHoliday'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'type' => ['required', 'in:check_in,check_out'],
        ]);

        $employee = $request->user()->employee;
        $today = Carbon::today()->toDateString();

        if (Carbon::parse($today)->isSunday()) {
            return back()->with('error', 'Hari ini libur. Absensi tidak dibuka.');
        }

        $now = Carbon::now();

        $attendance = Attendance::query()
            ->where('employee_id', $employee->id)
            ->whereDate('date', $today)
            ->first();

        if (! $attendance) {
            $attendance = new Attendance([
                'employee_id' => $employee->id,
                'date' => $today,
            ]);
        }

        if ($request->type === 'check_in') {
            if ($attendance->check_in) {
                return back()->with('error', 'Anda sudah check-in hari ini.');
            }

            $schedule = WorkSchedule::query()->where('shift_name', $employee->shift)->first();
            $status = 'on_time';
            $lateMinutes = 0;

            if ($schedule) {
                $expectedTime = Carbon::parse($today.' '.$schedule->start_time);
                $toleranceEnd = $expectedTime->copy()->addMinutes($schedule->late_tolerance);

                if ($now->gt($toleranceEnd)) {
                    $lateMinutes = $now->diffInMinutes($expectedTime);
                    $status = 'late';
                }
            }

            $attendance->check_in = $now->toTimeString();
            $attendance->status = $status;
            $attendance->late_minutes = $lateMinutes;
            $attendance->save();

            return back()->with('success', $status === 'late'
                ? "Check-in berhasil. Anda terlambat {$lateMinutes} menit."
                : 'Check-in berhasil. Tepat waktu.');
        }

        if (! $attendance->check_in) {
            return back()->with('error', 'Anda belum check-in hari ini.');
        }

        if ($attendance->check_out) {
            return back()->with('error', 'Anda sudah check-out hari ini.');
        }

        $attendance->check_out = $now->toTimeString();
        $attendance->save();

        return back()->with('success', 'Check-out berhasil.');
    }
}
