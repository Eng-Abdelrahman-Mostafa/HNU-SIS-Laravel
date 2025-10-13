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
        Schema::create('courses', function (Blueprint $table) {
            $table->id('course_id');
            $table->string('course_code', 20)->unique();
            $table->string('course_name', 200);
            $table->integer('credit_hours');
            $table->unsignedBigInteger('department_id')->nullable();
            $table->enum('course_type', ['M', 'E', 'G']);
            $table->enum('category', ['GEN', 'BAS', 'COM', 'DSC', 'MMD', 'RSE']);
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
        Schema::dropIfExists('courses');
    }
};
