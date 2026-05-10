<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE attendances MODIFY status ENUM('on_time','late','absent','permission','sick','alpha','holiday') NOT NULL DEFAULT 'on_time'");
        }
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("UPDATE attendances SET status = 'absent' WHERE status IN ('permission','sick','alpha','holiday')");
            DB::statement("ALTER TABLE attendances MODIFY status ENUM('on_time','late','absent') NOT NULL DEFAULT 'on_time'");
        }
    }
};
