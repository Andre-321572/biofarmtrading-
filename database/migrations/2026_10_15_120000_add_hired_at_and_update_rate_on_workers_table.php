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
        Schema::table('workers', function (Blueprint $table) {
            if (!Schema::hasColumn('workers', 'hired_at')) {
                $table->date('hired_at')->nullable()->after('shift')->index();
            }
            if (Schema::hasColumn('workers', 'is_new')) {
                $table->boolean('is_new')->nullable()->default(null)->change();
            }
            if (Schema::hasColumn('workers', 'hourly_rate')) {
                $table->decimal('hourly_rate', 10, 2)->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workers', function (Blueprint $table) {
            if (Schema::hasColumn('workers', 'hired_at')) {
                $table->dropColumn('hired_at');
            }
            if (Schema::hasColumn('workers', 'is_new')) {
                $table->boolean('is_new')->default(false)->change();
            }
            if (Schema::hasColumn('workers', 'hourly_rate')) {
                $table->unsignedInteger('hourly_rate')->nullable()->change();
            }
        });
    }
};
