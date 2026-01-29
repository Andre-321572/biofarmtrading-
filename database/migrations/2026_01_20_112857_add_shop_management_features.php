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
        // Add shop column to users for managers
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('shop_id')->nullable()->after('role')->constrained()->onDelete('set null');
        });

        // Create pivot table for shop inventory
        Schema::create('product_shop', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('shop_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(0);
            $table->timestamps();

            $table->unique(['product_id', 'shop_id']); // Each product appears only once per shop
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_shop');

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['shop_id']);
            $table->dropColumn('shop_id');
        });
    }
};
