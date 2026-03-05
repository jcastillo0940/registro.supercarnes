<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('fondas', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->after('event_id')->unique();
            $table->json('datos_extra')->nullable()->after('plato_preparar');
        });

        DB::table('fondas')->whereNull('uuid')->orderBy('id')->chunkById(100, function ($rows) {
            foreach ($rows as $row) {
                DB::table('fondas')->where('id', $row->id)->update(['uuid' => (string) Str::uuid()]);
            }
        });
    }

    public function down(): void
    {
        Schema::table('fondas', function (Blueprint $table) {
            $table->dropColumn('datos_extra');
            $table->dropUnique(['uuid']);
            $table->dropColumn('uuid');
        });
    }
};
