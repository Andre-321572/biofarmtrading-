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
            if (!Schema::hasColumn('workers', 'is_new')) {
                $table->boolean('is_new')->nullable()->default(null)->after('shift')->index();
            }
            if (!Schema::hasColumn('workers', 'hourly_rate')) {
                $table->unsignedInteger('hourly_rate')->nullable()->after('is_new');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workers', function (Blueprint $table) {
            if (Schema::hasColumn('workers', 'hourly_rate')) {
                $table->dropColumn('hourly_rate');
            }
            if (Schema::hasColumn('workers', 'is_new')) {
                $table->dropColumn('is_new');
            }
        });
    }
};
