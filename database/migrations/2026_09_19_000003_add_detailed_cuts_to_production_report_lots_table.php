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
        Schema::table('production_report_lots', function (Blueprint $table) {
            // RCL: Rondelle Cayenne Lisse
            $table->integer('sachets_rcl_2kg')->default(0)->after('sachets_500g');
            $table->integer('sachets_rcl_1kg')->default(0)->after('sachets_rcl_2kg');
            
            // MCL: Morceaux Cayenne Lisse
            $table->integer('sachets_mcl_2kg')->default(0)->after('sachets_rcl_1kg');
            $table->integer('sachets_mcl_1kg')->default(0)->after('sachets_mcl_2kg');
            
            // RPS: Rondelle Pain de Sucre
            $table->integer('sachets_rps_2kg')->default(0)->after('sachets_mcl_1kg');
            $table->integer('sachets_rps_1kg')->default(0)->after('sachets_rps_2kg');
            
            // MPS: Morceaux Pain de Sucre
            $table->integer('sachets_mps_2kg')->default(0)->after('sachets_rps_1kg');
            $table->integer('sachets_mps_1kg')->default(0)->after('sachets_mps_2kg');
            
            // LPA: Lamelles de Papaye
            $table->integer('sachets_lpa_2kg')->default(0)->after('sachets_mps_1kg');
            $table->integer('sachets_lpa_1kg')->default(0)->after('sachets_lpa_2kg');
            $table->integer('sachets_lpa_100g')->default(0)->after('sachets_lpa_1kg');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('production_report_lots', function (Blueprint $table) {
            $table->dropColumn([
                'sachets_rcl_2kg',
                'sachets_rcl_1kg',
                'sachets_mcl_2kg',
                'sachets_mcl_1kg',
                'sachets_rps_2kg',
                'sachets_rps_1kg',
                'sachets_mps_2kg',
                'sachets_mps_1kg',
                'sachets_lpa_2kg',
                'sachets_lpa_1kg',
                'sachets_lpa_100g',
            ]);
        });
    }
};
