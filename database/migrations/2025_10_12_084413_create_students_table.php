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
        Schema::create('students', function (Blueprint $table) {
            $table->string('student_id', 20)->primary();
            $table->string('full_name_arabic', 200);
            $table->string('email', 100)->unique();
            $table->string('password_hash', 255);
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('current_level_id')->nullable();
            $table->decimal('cgpa', 3, 2)->default(0.00);
            $table->decimal('total_points', 10, 2)->default(0.00);
            $table->integer('earned_credit_hours')->default(0);
            $table->integer('studied_credit_hours')->default(0);
            $table->integer('actual_credit_hours')->default(0);
            $table->enum('status', ['active', 'graduated', 'suspended'])->default('active');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            // Foreign keys
            $table->foreign('department_id')->references('department_id')->on('departments')->onDelete('restrict');
            $table->foreign('current_level_id')->references('level_id')->on('academic_levels')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
