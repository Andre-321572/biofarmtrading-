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
            $table->string('quantite_estimee')->nullable()->change();
            $table->renameColumn('quantite_estimee', 'calibre');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_invoices', function (Blueprint $table) {
            $table->renameColumn('calibre', 'quantite_estimee');
            $table->decimal('quantite_estimee', 15, 2)->default(0)->change();
        });
    }
};
