<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\InventoryItem;
use App\Services\MonthlyAttendanceReport;
use App\Support\AttendanceStatus;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    public function __invoke(MonthlyAttendanceReport $report)
    {
        $today = today();
        $activeEmployees = Employee::query()->where('is_active', true)->count();
        $todayAttendances = Attendance::query()->whereDate('date', $today)->get();
        $todaySummary = $this->summarize($todayAttendances);
        $todaySummary['total_active'] = $activeEmployees;
        $todaySummary['belum_absen'] = max($activeEmployees - $todayAttendances->count(), 0);

        $monthEmployees = $report->employees((int) $today->month, (int) $today->year);
        $monthSummary = [
            'average_score' => round($monthEmployees->avg('skor') ?? 0, 1),
            'best_count' => $monthEmployees->where('skor', $monthEmployees->max('skor'))->filter(fn ($employee) => ($employee['skor'] ?? 0) > 0)->count(),
            'total_late' => $monthEmployees->sum('terlambat'),
            'total_alpha' => $monthEmployees->sum('alfa'),
        ];

        $quarterStart = $today->copy()->firstOfQuarter()->startOfDay();
        $quarterEnd = $today->copy()->lastOfQuarter()->endOfDay();
        $quarterMonths = collect(range($quarterStart->month, $today->month));
        $quarterReports = $quarterMonths->map(fn ($month) => $report->employees((int) $month, (int) $today->year));
        $quarterSummary = [
            'label' => 'Q'.$today->quarter.' '.$today->year,
            'average_score' => round($quarterReports->flatten(1)->avg('skor') ?? 0, 1),
            'total_alpha' => Attendance::query()->whereBetween('date', [$quarterStart->toDateString(), min($today, $quarterEnd)->toDateString()])->get()->filter(fn ($attendance) => AttendanceStatus::isAlpha($attendance->status))->count(),
            'total_late' => Attendance::query()->whereBetween('date', [$quarterStart->toDateString(), min($today, $quarterEnd)->toDateString()])->where('status', AttendanceStatus::LATE)->count(),
        ];

        $lowStockItems = InventoryItem::query()->with('transactions')->where('is_active', true)->orderBy('name')->get()->filter->is_low_stock->values();

        return view('dashboard', compact('today', 'todaySummary', 'monthSummary', 'quarterSummary', 'lowStockItems'));
    }

    private function summarize(Collection $attendances): array
    {
        return [
            'hadir' => $attendances->filter(fn ($attendance) => AttendanceStatus::isPresent($attendance->status))->count(),
            'terlambat' => $attendances->where('status', AttendanceStatus::LATE)->count(),
            'izin' => $attendances->where('status', AttendanceStatus::PERMISSION)->count(),
            'sakit' => $attendances->where('status', AttendanceStatus::SICK)->count(),
            'alfa' => $attendances->filter(fn ($attendance) => AttendanceStatus::isAlpha($attendance->status))->count(),
            'libur' => $attendances->where('status', AttendanceStatus::HOLIDAY)->count(),
        ];
    }
}
