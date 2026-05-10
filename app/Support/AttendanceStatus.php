<?php

namespace App\Support;

class AttendanceStatus
{
    public const ON_TIME = 'on_time';
    public const LATE = 'late';
    public const ABSENT = 'absent';
    public const PERMISSION = 'permission';
    public const SICK = 'sick';
    public const ALPHA = 'alpha';
    public const HOLIDAY = 'holiday';

    public static function values(): array
    {
        return [self::ON_TIME, self::LATE, self::ABSENT, self::PERMISSION, self::SICK, self::ALPHA, self::HOLIDAY];
    }

    public static function absenceValues(): array
    {
        return [self::PERMISSION, self::SICK, self::ALPHA, self::HOLIDAY];
    }

    public static function presentValues(): array
    {
        return [self::ON_TIME, self::LATE];
    }

    public static function alphaValues(): array
    {
        return [self::ABSENT, self::ALPHA];
    }

    public static function normalized(?string $status): ?string
    {
        return match ($status) {
            'izin' => self::PERMISSION,
            'sakit' => self::SICK,
            'alfa' => self::ALPHA,
            'libur' => self::HOLIDAY,
            default => $status,
        };
    }

    public static function label(?string $status): string
    {
        return match (self::normalized($status)) {
            self::ON_TIME => 'Hadir',
            self::LATE => 'Terlambat',
            self::ABSENT, self::ALPHA => 'Alfa',
            self::PERMISSION => 'Izin',
            self::SICK => 'Sakit',
            self::HOLIDAY => 'Libur',
            default => 'Belum Ada Data',
        };
    }

    public static function badgeClass(?string $status): string
    {
        return match (self::normalized($status)) {
            self::ON_TIME => 'status-on-time',
            self::LATE => 'status-late',
            self::PERMISSION => 'status-permission',
            self::SICK => 'status-sick',
            self::ABSENT, self::ALPHA => 'status-absent',
            self::HOLIDAY => 'status-holiday',
            default => 'status-empty',
        };
    }

    public static function isPresent(?string $status): bool
    {
        return in_array(self::normalized($status), self::presentValues(), true);
    }

    public static function isAlpha(?string $status): bool
    {
        return in_array(self::normalized($status), self::alphaValues(), true);
    }
}
