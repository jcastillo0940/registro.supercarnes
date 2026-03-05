<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('fondas', function (Blueprint $table) {
            $table->dropUnique('fondas_cedula_unique');
            $table->unique(['event_id', 'cedula']);
        });
    }

    public function down(): void
    {
        Schema::table('fondas', function (Blueprint $table) {
            $table->dropUnique('fondas_event_id_cedula_unique');
            $table->unique('cedula');
        });
    }
};
