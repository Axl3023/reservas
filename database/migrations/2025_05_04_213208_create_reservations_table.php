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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('customer_phone')->nullable();
            $table->string('customer_email')->nullable();
            $table->date('reservation_date');
            $table->time('reservation_time');
            $table->integer('guest_count');
            $table->unsignedBigInteger('table_id');
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('employee_id')->nullable(); // Lo hace opcional
            $table->timestamps();

            // Foreign Keys
            $table->foreign('table_id')->references('id')->on('tables')->restrictOnDelete();
            $table->foreign('employee_id')->references('id')->on('employees')->nullOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
