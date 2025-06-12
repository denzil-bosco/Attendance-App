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
        if (!Schema::hasTable('students')) {
            Schema::create('students', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('first_name', 255);
                $table->string('last_name', 255);
                $table->date('date_of_birth')->nullable();
                $table->enum('gender', ['MALE', 'FEMALE', 'OTHER'])->nullable();
                $table->string('contact_person_name')->nullable();
                $table->string('contact_person_phone', 20)->nullable();
                $table->date('enrollment_date')->nullable();
                $table->string('student_id_number', 255)->unique()->nullable();
                $table->unsignedBigInteger('class_id'); 
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
