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
        Schema::create('maintenance_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('maintenance_contract_id')->constrained()->cascadeOnDelete();

            $table->date('due_date');          // wann soll gewartet werden
            $table->date('booking_window_start')->nullable();
            $table->date('booking_window_end')->nullable();

            $table->enum('status', ['pending', 'proposed', 'confirmed', 'completed', 'cancelled'])
                ->default('pending');

            $table->foreignId('appointment_id')->nullable()->constrained()->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_tasks');
    }
};
