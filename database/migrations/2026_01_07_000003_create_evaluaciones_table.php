<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('evaluaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('fonda_id')->constrained()->onDelete('cascade');
            $table->foreignId('criterio_id')->constrained()->onDelete('cascade');
            $table->integer('puntaje');
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('evaluaciones'); }
};