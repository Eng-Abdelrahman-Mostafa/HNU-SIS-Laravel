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
        Schema::create('student_course_grades', function (Blueprint $table) {
            $table->id('grade_id');
            $table->unsignedBigInteger('enrollment_id')->unique();
            $table->decimal('marks', 5, 2)->nullable();
            $table->decimal('grade_percent', 5, 2)->nullable();
            $table->string('grade_letter', 5)->nullable();
            $table->decimal('points', 5, 2)->nullable();
            $table->integer('credit_hours')->nullable();
            $table->integer('act_credit_hours')->nullable();
            $table->timestamps();

            $table->foreign('enrollment_id')
                  ->references('enrollment_id')
                  ->on('enrollments')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_course_grades');
    }
};
