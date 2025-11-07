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
        Schema::table('semesters', function (Blueprint $table) {
            $table->boolean('is_active')->default(false)->after('end_date');
            $table->dateTime('student_registeration_start_at')->nullable()->after('is_active');
            $table->dateTime('student_registeration_end_at')->nullable()->after('student_registeration_start_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('semesters', function (Blueprint $table) {
            $table->dropColumn(['is_active', 'student_registeration_start_at', 'student_registeration_end_at']);
        });
    }
};
