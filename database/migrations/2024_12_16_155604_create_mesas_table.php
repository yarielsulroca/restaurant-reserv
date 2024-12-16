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
        Schema::create('mesas', function (Blueprint $table) { 
            $table->id(); 
            $table->integer('numero')->unique(); 
            $table->integer('capacidad'); 
            $table->enum('zona', ['Interior', 'Exterior', 'Terraza'])->default('Interior'); 
            $table->string('ubicacion')->nullable(); 
            $table->enum('status', ['available', 'occupied', 'reserved'])->default('available'); 
            $table->string('estado')->default('disponible'); 
            $table->timestamps(); });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mesas');
    }
};
