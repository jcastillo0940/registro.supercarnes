<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('fondas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_persona');
            $table->string('cedula')->unique();
            $table->string('telefono');
            $table->string('nombre_fonda');
            $table->text('ubicacion');
            $table->string('plato_preparar');
            $table->string('qr_code')->nullable();
            $table->decimal('ajuste_admin', 8, 2)->default(0);
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('fondas'); }
};