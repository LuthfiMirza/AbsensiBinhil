<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::create('work_schedules', function (Blueprint $table) {
        $table->id();
        $table->string('shift_name');          // pagi, siang, sore
        $table->time('start_time');            // jam masuk
        $table->time('end_time');              // jam pulang
        $table->integer('late_tolerance')->default(15); // toleransi terlambat (menit)
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_schedules');
    }
};
