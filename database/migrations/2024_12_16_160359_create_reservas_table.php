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
        Schema::create('reservas', function (Blueprint $table) {
            
            $table->id(); 
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            $table->foreignId('mesa_id')->constrained()->onDelete('cascade'); 
            $table->date('fecha'); 
            $table->time('hora'); 
            $table->integer('numero_personas'); 
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending'); 
            $table->boolean('expired')->default(false); 
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};
