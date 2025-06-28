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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('customer_name');
            $table->string('customer_email');
            $table->date('booking_date');
            $table->enum('booking_type', ['full_day', 'half_day', 'custom']);
            $table->enum('booking_slot', ['first_half', 'second_half'])->nullable();
            $table->time('booking_from_time')->nullable();
            $table->time('booking_to_time')->nullable();
            $table->timestamps();
            
            // Critical indexes for performance optimization
            $table->index(['booking_date', 'booking_type']);
            $table->index(['booking_date', 'booking_slot']);
            $table->index(['booking_date', 'booking_from_time', 'booking_to_time']);
            $table->index('user_id');
            
            // Composite index for overlap detection
            $table->index(['booking_date', 'booking_type', 'booking_slot', 'booking_from_time', 'booking_to_time'], 'booking_overlap_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
