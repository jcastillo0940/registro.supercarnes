<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() {
    Schema::table('fondas', function (Blueprint $table) {
        $table->integer('orden_visita')->default(0); // Para organizar la ruta
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fondas', function (Blueprint $table) {
            //
        });
    }
};
