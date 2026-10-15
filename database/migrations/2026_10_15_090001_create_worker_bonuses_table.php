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
        Schema::create('worker_bonuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('worker_id')->constrained('workers')->onDelete('cascade');
            $table->string('month', 7)->index(); // Format: YYYY-MM
            $table->unsignedInteger('amount')->default(0); // Montant en FCFA
            $table->string('reason')->nullable(); // Motif de la prime (ex: "Prime de rendement")
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            // Index composite pour recherches rapides par ouvrier et mois
            $table->index(['worker_id', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('worker_bonuses');
    }
};
