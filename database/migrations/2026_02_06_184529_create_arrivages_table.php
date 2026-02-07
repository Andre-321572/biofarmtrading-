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
        Schema::create('arrivages', function (Blueprint $table) {
            $table->id();
            $table->string('chauffeur');
            $table->string('matricule_camion');
            $table->date('date_arrivage');
            $table->string('zone_provenance');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('arrivage_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('arrivage_id')->constrained()->cascadeOnDelete();
            $table->string('fruit'); // ananas, papaye
            $table->string('variete')->nullable(); // cayenne_lisse, braza
            $table->decimal('poids', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arrivage_details');
        Schema::dropIfExists('arrivages');
    }
};
