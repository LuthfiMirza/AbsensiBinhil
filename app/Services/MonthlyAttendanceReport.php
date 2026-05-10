<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Employee;
use App\Support\AttendanceStatus;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;

class MonthlyAttendanceReport
{
    public function years(): array
    {
        $currentYear = now()->year;
        $firstYear = Attendance::query()->min('date');
        $startYear = $firstYear ? Carbon::parse($firstYear)->year : $currentYear;

        return range(min($startYear, $currentYear), max($startYear, $currentYear));
    }

    public function areas(): Collection
    {
        return Employee::query()
            ->whereNotNull('area')
            ->where('area', '!=', '')
            ->distinct()
            ->orderBy('area')
            ->pluck('area');
    }

    public function employees(int $month, int $year, ?string $area = null): Collection
    {
        $baseWorkingDays = $this->workingDays($month, $year);

        return Employee::query()
            ->where('is_active', true)
            ->when($area, fn ($query) => $query->where('area', $area))
            ->with(['attendances' => function ($query) use ($month, $year) {
                $query->whereMonth('date', $month)->whereYear('date', $year);
            }])
            ->get()
            ->map(function (Employee $employee) use ($baseWorkingDays) {
                $attendances = $employee->attendances->filter(
                    fn (Attendance $attendance) => $this->isWorkingDay($attendance->date)
                );
                $summary = $this->summary($attendances, $baseWorkingDays);

                return array_merge([
                    'id' => $employee->id,
                    'name' => $employee->name,
                    'area' => $employee->area,
                    'shift' => $employee->shift,
                ], $summary);
            })
            ->sortByDesc('skor')
            ->values();
    }

    public function detail(Employee $employee, int $month, int $year): Collection
    {
        $attendances = $employee->attendances()
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get()
            ->keyBy(fn (Attendance $attendance) => $attendance->date->toDateString());

        return collect(CarbonPeriod::create(
            Carbon::createFromDate($year, $month, 1)->startOfDay(),
            Carbon::createFromDate($year, $month, 1)->endOfMonth()->startOfDay()
        ))->map(function (Carbon $date) use ($employee, $attendances) {
            $attendance = $attendances->get($date->toDateString());
            $status = $attendance?->status;

            if (! $this->isWorkingDay($date) && ! $status) {
                $status = AttendanceStatus::HOLIDAY;
            }

            return [
                'date' => $date->copy(),
                'area' => $employee->area,
                'is_working_day' => $this->isWorkingDay($date),
                'attendance' => $attendance,
                'status' => $status,
                'status_label' => AttendanceStatus::label($status),
                'status_class' => AttendanceStatus::badgeClass($status),
                'check_in' => $attendance?->check_in,
                'check_out' => $attendance?->check_out,
                'late_minutes' => $attendance?->late_minutes ?? 0,
            ];
        });
    }

    public function workingDays(int $month, int $year): int
    {
        return collect(CarbonPeriod::create(
            Carbon::createFromDate($year, $month, 1)->startOfDay(),
            Carbon::createFromDate($year, $month, 1)->endOfMonth()->startOfDay()
        ))->filter(fn (Carbon $date) => $this->isWorkingDay($date))->count();
    }

    public function isWorkingDay(Carbon $date): bool
    {
        return ! $date->isSunday();
    }

    public function summary(Collection $attendances, int $baseWorkingDays): array
    {
        $hadir = $attendances->filter(fn (Attendance $attendance) => AttendanceStatus::isPresent($attendance->status))->count();
        $onTime = $attendances->where('status', AttendanceStatus::ON_TIME)->count();
        $late = $attendances->where('status', AttendanceStatus::LATE)->count();
        $permission = $attendances->where('status', AttendanceStatus::PERMISSION)->count();
        $sick = $attendances->where('status', AttendanceStatus::SICK)->count();
        $alpha = $attendances->filter(fn (Attendance $attendance) => AttendanceStatus::isAlpha($attendance->status))->count();
        $holiday = $attendances->where('status', AttendanceStatus::HOLIDAY)->count();
        $effectiveWorkingDays = max($baseWorkingDays - $holiday, 0);
        $recorded = $hadir + $permission + $sick + $alpha + $holiday;
        $noData = max($effectiveWorkingDays - ($hadir + $permission + $sick + $alpha), 0);

        return [
            'hadir' => $hadir,
            'on_time' => $onTime,
            'terlambat' => $late,
            'izin' => $permission,
            'sakit' => $sick,
            'alfa' => $alpha,
            'libur' => $holiday,
            'tidak_hadir' => $alpha,
            'belum_ada_data' => $noData,
            'recorded' => $recorded,
            'avg_terlambat' => round($attendances->where('late_minutes', '>', 0)->avg('late_minutes') ?? 0, 1),
            'skor' => $this->score($hadir, $onTime, $permission, $sick, $alpha, $noData, $effectiveWorkingDays),
        ];
    }

    public function score(int $hadir, int $onTime, int $permission, int $sick, int $alpha, int $noData, int $workingDays): float
    {
        if ($workingDays === 0) {
            return 0;
        }

        $attendanceCredit = $hadir + ($permission * 0.75) + ($sick * 0.85);
        $attendanceScore = min(($attendanceCredit / $workingDays) * 70, 70);
        $punctualityScore = $hadir > 0 ? ($onTime / $hadir) * 30 : 0;
        $penalty = ($alpha * 5) + ($noData * 3);

        return round(max($attendanceScore + $punctualityScore - $penalty, 0), 1);
    }
}
