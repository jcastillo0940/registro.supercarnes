<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('criterios', function (Blueprint $table) {
            $table->foreignId('event_id')->nullable()->after('id')->constrained('events')->cascadeOnDelete();
        });

        $eventId = DB::table('events')->value('id');
        if ($eventId) {
            DB::table('criterios')->whereNull('event_id')->update(['event_id' => $eventId]);
        }
    }

    public function down(): void
    {
        Schema::table('criterios', function (Blueprint $table) {
            $table->dropConstrainedForeignId('event_id');
        });
    }
};
