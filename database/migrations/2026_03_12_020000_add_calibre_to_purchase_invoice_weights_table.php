<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchase_invoice_weights', function (Blueprint $table) {
            $table->string('calibre', 20)->default('PF')->after('weight');
        });
    }

    public function down(): void
    {
        Schema::table('purchase_invoice_weights', function (Blueprint $table) {
            $table->dropColumn('calibre');
        });
    }
};
