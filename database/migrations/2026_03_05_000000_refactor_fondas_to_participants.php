<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. Renombrar la tabla 'fondas' a 'participants'
        Schema::rename('fondas', 'participants');

        // 2. Renombrar 'fonda_id' a 'participant_id' en la tabla 'evaluaciones'
        Schema::table('evaluaciones', function (Blueprint $table) {
            $table->renameColumn('fonda_id', 'participant_id');
        });

        // 3. Ya que 'public_votes' apunta a 'fondas', Laravel lo resolverá si la tabla cambia de nombre,
        // pero es mejor asegurar la relación si el motor de BD lo requiere.
        // En este caso, renombramos el índice y FK implícita si es necesario.
    }

    public function down(): void
    {
        Schema::table('evaluaciones', function (Blueprint $table) {
            $table->renameColumn('participant_id', 'fonda_id');
        });

        Schema::rename('participants', 'fondas');
    }
};
