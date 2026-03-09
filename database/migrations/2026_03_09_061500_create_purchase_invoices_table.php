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
        Schema::create('purchase_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('bon_no')->unique();
            $table->date('date_invoice');
            $table->string('prefecture')->nullable();
            $table->string('zone')->nullable();
            $table->string('chauffeur')->nullable();
            $table->string('fruit')->nullable();
            $table->string('op')->nullable();
            $table->string('producteur')->nullable();
            $table->string('code_parcelle_matricule')->nullable();
            $table->decimal('quantite_estimee', 15, 2)->default(0);
            $table->decimal('pu', 15, 2)->default(0);
            $table->decimal('prime_bio_kg', 15, 2)->default(0)->nullable();
            $table->decimal('poids_avarie', 15, 2)->default(0)->nullable();
            $table->decimal('poids_marchand', 15, 2)->default(0)->nullable();
            $table->string('net_payer_lettre')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('purchase_invoice_weights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_invoice_id')->constrained()->onDelete('cascade');
            $table->integer('position');
            $table->decimal('weight', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_invoice_weights');
        Schema::dropIfExists('purchase_invoices');
    }
};
