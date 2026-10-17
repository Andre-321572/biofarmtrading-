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
        Schema::table('employees', function (Blueprint $table) {
            if (!Schema::hasColumn('employees', 'worker_id')) {
                $table->foreignId('worker_id')->nullable()->after('job_position_id')->constrained('workers')->nullOnDelete();
            }
        });

        Schema::table('workers', function (Blueprint $table) {
            if (!Schema::hasColumn('workers', 'job_position_id')) {
                $table->foreignId('job_position_id')->nullable()->after('position')->constrained('job_positions')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            if (Schema::hasColumn('employees', 'worker_id')) {
                $table->dropForeign(['worker_id']);
                $table->dropColumn('worker_id');
            }
        });

        Schema::table('workers', function (Blueprint $table) {
            if (Schema::hasColumn('workers', 'job_position_id')) {
                $table->dropForeign(['job_position_id']);
                $table->dropColumn('job_position_id');
            }
        });
    }
};
