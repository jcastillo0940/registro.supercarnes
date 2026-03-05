<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('public_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->foreignId('participant_id')->constrained('fondas')->cascadeOnDelete();
            $table->string('voter_fingerprint', 64);
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 255)->nullable();
            $table->json('scores');
            $table->timestamps();

            $table->unique(['event_id', 'participant_id', 'voter_fingerprint'], 'public_votes_unique_vote');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('public_votes');
    }
};
