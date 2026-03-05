<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('evaluaciones', function (Blueprint $table) {
            // 1. Eliminamos la restricción vieja que está causando el error
            // El nombre suele ser 'evaluaciones_user_id_fonda_id_unique'
            $table->dropUnique(['user_id', 'fonda_id']);

            // 2. Creamos la nueva restricción que incluya el criterio
            $table->unique(['user_id', 'fonda_id', 'criterio_id'], 'evaluacion_unica_por_criterio');
        });
    }

    public function down()
    {
        Schema::table('evaluaciones', function (Blueprint $table) {
            $table->dropUnique('evaluacion_unica_por_criterio');
            $table->unique(['user_id', 'fonda_id']);
        });
    }
};