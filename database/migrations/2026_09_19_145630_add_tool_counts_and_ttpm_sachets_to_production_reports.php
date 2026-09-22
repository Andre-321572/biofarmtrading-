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
            $table->integer('nombre_couteaux_debut')->nullable()->after('nombre_ouvriers_repos');
            $table->integer('nombre_couteaux_fin')->nullable()->after('nombre_couteaux_debut');
            $table->integer('nombre_ciseaux_debut')->nullable()->after('nombre_couteaux_fin');
            $table->integer('nombre_ciseaux_fin')->nullable()->after('nombre_ciseaux_debut');
        });

        Schema::table('production_report_lots', function (Blueprint $table) {
            $table->integer('sachets_ttpm_1kg')->nullable()->after('sachets_lpa_100g');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('production_reports', function (Blueprint $table) {
            $table->dropColumn(['nombre_couteaux_debut', 'nombre_couteaux_fin', 'nombre_ciseaux_debut', 'nombre_ciseaux_fin']);
        });

        Schema::table('production_report_lots', function (Blueprint $table) {
            $table->dropColumn(['sachets_ttpm_1kg']);
        });
    }
};
