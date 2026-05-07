<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Employee;
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
        $workingDays = $this->workingDays($month, $year);

        return Employee::query()
            ->where('is_active', true)
            ->when($area, fn ($query) => $query->where('area', $area))
            ->with(['attendances' => function ($query) use ($month, $year) {
                $query->whereMonth('date', $month)->whereYear('date', $year);
            }])
            ->get()
            ->map(function (Employee $employee) use ($workingDays) {
                $attendances = $employee->attendances->filter(
                    fn (Attendance $attendance) => $this->isWorkingDay($attendance->date)
                );

                return [
                    'id' => $employee->id,
                    'name' => $employee->name,
                    'area' => $employee->area,
                    'shift' => $employee->shift,
                    'hadir' => $attendances->whereNotIn('status', ['absent'])->count(),
                    'on_time' => $attendances->where('status', 'on_time')->count(),
                    'terlambat' => $attendances->where('status', 'late')->count(),
                    'tidak_hadir' => $attendances->where('status', 'absent')->count(),
                    'avg_terlambat' => round($attendances->where('late_minutes', '>', 0)->avg('late_minutes') ?? 0, 1),
                    'skor' => $this->score($attendances, $workingDays),
                ];
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

            return [
                'date' => $date->copy(),
                'area' => $employee->area,
                'is_working_day' => $this->isWorkingDay($date),
                'attendance' => $attendance,
                'status' => $attendance?->status,
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

    public function score(Collection $attendances, int $workingDays): float
    {
        if ($workingDays === 0) {
            return 0;
        }

        $hadir = $attendances->whereNotIn('status', ['absent'])->count();
        $onTime = $attendances->where('status', 'on_time')->count();

        return round((($hadir / $workingDays) * 70) + (($onTime / max($hadir, 1)) * 30), 1);
    }
}
