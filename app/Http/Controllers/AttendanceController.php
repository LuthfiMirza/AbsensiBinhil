<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\WorkSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        $today = Carbon::today()->toDateString();
        $attendances = Attendance::with('employee')
                        ->where('date', $today)
                        ->latest()
                        ->get();

        // Counter harian
        $counter = [
            'hadir'       => $attendances->whereNotIn('status', ['absent'])->count(),
            'on_time'     => $attendances->where('status', 'on_time')->count(),
            'terlambat'   => $attendances->where('status', 'late')->count(),
            'belum_absen' => Employee::where('is_active', true)->count()
                             - $attendances->count(),
        ];

        return view('attendances.index', compact('attendances', 'counter', 'today'));
    }

    public function create()
    {
        $employees = Employee::where('is_active', true)->get();
        return view('attendances.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'type'        => 'required|in:check_in,check_out',
        ]);

        $employee = Employee::findOrFail($request->employee_id);
        $today    = Carbon::today()->toDateString();
        $now      = Carbon::now();

        // Cek apakah sudah ada record hari ini
        $attendance = Attendance::firstOrNew([
            'employee_id' => $employee->id,
            'date'        => $today,
        ]);

        if ($request->type === 'check_in') {
            if ($attendance->check_in) {
                return back()->with('error', 'Petugas sudah check-in hari ini!');
            }

            // Hitung status keterlambatan
            $schedule = WorkSchedule::where('shift_name', $employee->shift)->first();
            $status = 'on_time';
            $lateMinutes = 0;

            if ($schedule) {
                $expectedTime = Carbon::parse($today . ' ' . $schedule->start_time);
                $toleranceEnd = $expectedTime->copy()->addMinutes($schedule->late_tolerance);

                if ($now->gt($toleranceEnd)) {
                    $lateMinutes = $now->diffInMinutes($expectedTime);
                    $status = 'late';
                }
            }

            $attendance->check_in    = $now->toTimeString();
            $attendance->status      = $status;
            $attendance->late_minutes = $lateMinutes;
            $attendance->save();

            $msg = $status === 'late'
                ? "Check-in berhasil — Terlambat {$lateMinutes} menit"
                : "Check-in berhasil — Tepat waktu ✓";

            return redirect()->route('attendances.index')->with('success', $msg);
        }

        if ($request->type === 'check_out') {
            if (!$attendance->check_in) {
                return back()->with('error', 'Petugas belum check-in!');
            }
            if ($attendance->check_out) {
                return back()->with('error', 'Petugas sudah check-out hari ini!');
            }

            $attendance->check_out = $now->toTimeString();
            $attendance->save();

            return redirect()->route('attendances.index')
                             ->with('success', 'Check-out berhasil!');
        }
    }

    // Tandai tidak hadir (manual oleh admin)
    public function markAbsent(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date'        => 'required|date',
        ]);

        Attendance::updateOrCreate(
            ['employee_id' => $request->employee_id, 'date' => $request->date],
            ['status' => 'absent', 'notes' => $request->notes]
        );

        return redirect()->route('attendances.index')
                         ->with('success', 'Absensi tidak hadir dicatat.');
    }
}