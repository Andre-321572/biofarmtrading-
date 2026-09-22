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
        Schema::table('production_reports', function (Blueprint $table) {
            $table->integer('nombre_ouvriers_absents')->default(0)->after('nombre_ouvriers');
            $table->integer('nombre_ouvriers_repos')->default(0)->after('nombre_ouvriers_absents');
        });

        Schema::table('production_report_lots', function (Blueprint $table) {
            $table->decimal('poids_rcl_kg', 10, 2)->default(0)->after('quantite_declayee_kg');
            $table->decimal('poids_mcl_kg', 10, 2)->default(0)->after('poids_rcl_kg');
            $table->decimal('poids_rps_kg', 10, 2)->default(0)->after('poids_mcl_kg');
            $table->decimal('poids_mps_kg', 10, 2)->default(0)->after('poids_rps_kg');
            $table->decimal('poids_lpa_kg', 10, 2)->default(0)->after('poids_mps_kg');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('production_reports', function (Blueprint $table) {
            $table->dropColumn(['nombre_ouvriers_absents', 'nombre_ouvriers_repos']);
        });

        Schema::table('production_report_lots', function (Blueprint $table) {
            $table->dropColumn(['poids_rcl_kg', 'poids_mcl_kg', 'poids_rps_kg', 'poids_mps_kg', 'poids_lpa_kg']);
        });
    }
};
