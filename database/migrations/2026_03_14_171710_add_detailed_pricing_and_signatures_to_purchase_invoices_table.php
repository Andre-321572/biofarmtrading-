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
        Schema::table('purchase_invoices', function (Blueprint $table) {
            $table->decimal('pu_pf', 15, 2)->default(0)->nullable()->after('pu');
            $table->decimal('pu_gf', 15, 2)->default(0)->nullable()->after('pu_pf');
            $table->decimal('total_credit', 15, 2)->default(0)->nullable()->after('poids_marchand');
            $table->longText('signature_resp')->nullable();
            $table->longText('signature_prod')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('purchase_invoices', function (Blueprint $table) {
            $table->dropColumn(['pu_pf', 'pu_gf', 'total_credit', 'signature_resp', 'signature_prod']);
        });
    }
};
