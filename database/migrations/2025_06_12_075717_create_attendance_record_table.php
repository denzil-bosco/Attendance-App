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
        if (!Schema::hasTable('attendance_record')) {
            Schema::create('attendance_record', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('student_id');
                $table->unsignedBigInteger('class_id');
                $table->date('date');
                $table->enum('status', ['PRESENT', 'ABSENT', 'LATE'])->default('ABSENT');
                $table->time('check_in_time')->nullable();
                $table->string('absence_reason')->nullable();
                $table->text('remarks')->nullable();
                $table->unsignedBigInteger('recorded_by');
                $table->timestamps();
                $table->unique(['student_id', 'date']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_record');
    }
};
