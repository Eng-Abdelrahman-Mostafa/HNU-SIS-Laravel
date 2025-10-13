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
        Schema::create('instructors', function (Blueprint $table) {
            $table->id('instructor_id');
            $table->string('instructor_code', 20)->unique()->nullable();
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('full_name_arabic', 200)->nullable();
            $table->string('email', 100)->unique()->nullable();
            $table->string('phone', 20)->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->enum('title', ['Dr.', 'Prof.', 'Lecturer', 'TA'])->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamp('created_at')->useCurrent();

            // Foreign key
            $table->foreign('department_id')->references('department_id')->on('departments')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instructors');
    }
};
