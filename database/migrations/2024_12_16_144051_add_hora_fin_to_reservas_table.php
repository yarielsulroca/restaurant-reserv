<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservas', function (Blueprint $table) {
            $table->time('hora_fin')->after('hora');
            $table->enum('status', ['pending', 'confirmed', 'expired', 'completed', 'cancelled'])
                ->default('pending')
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('reservas', function (Blueprint $table) {
            $table->dropColumn('hora_fin');
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])
                ->default('pending')
                ->change();
        });
    }
};
