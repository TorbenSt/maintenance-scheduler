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
        Schema::create('maintenance_intervals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();

            $table->string('name'); // z.B. "Heizungswartung"
            $table->text('description')->nullable();
            $table->unsignedInteger('interval_months'); // 12
            $table->unsignedInteger('booking_window_days')->default(30); // 30 Tage vorher
            $table->unsignedInteger('estimated_duration_minutes')->default(60);

            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_intervals');
    }
};
