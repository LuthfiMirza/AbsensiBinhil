<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Services\MonthlyAttendanceReport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function monthly(Request $request, MonthlyAttendanceReport $report)
    {
        $month = (int) ($request->month ?? now()->month);
        $year = (int) ($request->year ?? now()->year);
        $area = $request->filled('area') ? $request->area : null;

        $employees = $report->employees($month, $year, $area);
        $areas = $report->areas();
        $years = $report->years();
        $workingDays = $report->workingDays($month, $year);
        $bulan = Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y');

        return view('reports.monthly', compact(
            'employees',
            'month',
            'year',
            'area',
            'areas',
            'years',
            'workingDays',
            'bulan'
        ));
    }

    public function monthlyDetail(Request $request, Employee $employee, MonthlyAttendanceReport $report)
    {
        $month = (int) ($request->month ?? now()->month);
        $year = (int) ($request->year ?? now()->year);

        $days = $report->detail($employee, $month, $year);
        $workingDays = $report->workingDays($month, $year);
        $bulan = Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y');

        return view('reports.monthly-detail', compact(
            'employee',
            'days',
            'month',
            'year',
            'workingDays',
            'bulan'
        ));
    }

    public function exportMonthly(Request $request, MonthlyAttendanceReport $report): StreamedResponse
    {
        $month = (int) ($request->month ?? now()->month);
        $year = (int) ($request->year ?? now()->year);
        $area = $request->filled('area') ? $request->area : null;
        $employees = $report->employees($month, $year, $area);
        $filename = 'laporan-bulanan-'.$year.'-'.str_pad((string) $month, 2, '0', STR_PAD_LEFT).'.csv';

        return response()->streamDownload(function () use ($employees) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Nama petugas',
                'Area/blok',
                'Total hadir',
                'Tepat waktu',
                'Terlambat',
                'Izin',
                'Sakit',
                'Alfa',
                'Libur',
                'Belum ada data',
                'Rata-rata telat',
                'Skor performa',
            ]);

            foreach ($employees as $employee) {
                fputcsv($handle, [
                    $employee['name'],
                    $employee['area'],
                    $employee['hadir'],
                    $employee['on_time'],
                    $employee['terlambat'],
                    $employee['izin'],
                    $employee['sakit'],
                    $employee['alfa'],
                    $employee['libur'],
                    $employee['belum_ada_data'],
                    $employee['avg_terlambat'],
                    $employee['skor'],
                ]);
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
