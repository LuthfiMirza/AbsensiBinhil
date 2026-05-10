<?php

use App\Models\Employee;
use App\Models\InventoryItem;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(InventoryItem::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Employee::class)->nullable()->constrained()->nullOnDelete();
            $table->date('transaction_date');
            $table->enum('type', ['in', 'out', 'allocation']);
            $table->unsignedInteger('quantity');
            $table->string('area')->nullable();
            $table->string('source')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');
    }
};
