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
        Schema::create('clever_reach_forms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('external_id');
            $table->foreignId('clever_reach_client_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->unsignedBigInteger('customer_tables_id')->nullable();
            $table->timestamps();

            $table->unique(['clever_reach_client_id', 'external_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clever_reach_forms');
    }
};
