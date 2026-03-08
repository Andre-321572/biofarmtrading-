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
        Schema::table('arrivages', function (Blueprint $table) {
            $table->string('ph')->nullable()->after('zone_provenance');
            $table->string('brix')->nullable()->after('ph');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('arrivages', function (Blueprint $table) {
            $table->dropColumn(['ph', 'brix']);
        });
    }
};
