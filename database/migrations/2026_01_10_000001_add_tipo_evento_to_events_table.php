<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('tipo_evento', 50)->default('general')->after('slug');
        });

        DB::table('events')->where('slug', 'like', '%rock%')->update(['tipo_evento' => 'rock_fest']);
        DB::table('events')->where('slug', 'like', '%bbq%')->update(['tipo_evento' => 'bbq_challenge']);
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('tipo_evento');
        });
    }
};
