<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('fondas', function (Blueprint $table) {
            $table->foreignId('event_id')->nullable()->after('id')->constrained('events')->nullOnDelete();
        });

        $eventId = DB::table('events')->insertGetId([
            'nombre' => 'Evento Base',
            'slug' => 'evento-base',
            'tipo_votacion' => 'jurado',
            'estado' => 'activo',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('fondas')->whereNull('event_id')->update(['event_id' => $eventId]);
    }

    public function down(): void
    {
        Schema::table('fondas', function (Blueprint $table) {
            $table->dropConstrainedForeignId('event_id');
        });

        DB::table('events')->where('slug', 'evento-base')->delete();
    }
};
