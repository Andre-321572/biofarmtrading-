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
        Schema::create('production_report_lots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_report_id')->constrained('production_reports')->onDelete('cascade');
            $table->string('numero_lot');
            $table->string('type_produit')->nullable(); // Ananas, Papaye, etc.
            $table->decimal('quantite_declayee_kg', 10, 2)->default(0); // CL en kg
            $table->integer('sachets_2kg')->default(0); // MCL 2kg
            $table->integer('sachets_1kg')->default(0); // MCL 1kg
            $table->integer('sachets_500g')->default(0); // MCL 500g
            $table->string('type_coupe')->nullable(); // Rondelle (R), Morceau (M), TTPM
            $table->decimal('dechets_kg', 10, 2)->default(0); // Déchets en kg
            $table->decimal('cl_ttpm_kg', 10, 2)->default(0); // CL affectés au TTPM / TTPM coupés
            $table->decimal('pa_kg', 10, 2)->default(0); // Poids PA
            $table->text('pelage_info')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_report_lots');
    }
};
