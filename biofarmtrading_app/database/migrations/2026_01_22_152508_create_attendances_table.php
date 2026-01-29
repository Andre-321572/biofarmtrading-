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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('worker_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->enum('session', ['morning', 'afternoon'])->default('morning'); // A = Morning, D = Afternoon (assuming)
            $table->enum('status', ['present', 'absent', 'late', 'none'])->default('none');
            $table->timestamps();
            
            // Unique constraint: one record per worker per session per date
            $table->unique(['worker_id', 'date', 'session']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
