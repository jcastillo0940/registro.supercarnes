<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Criterio;

class FondaChallengeSeeder extends Seeder {
    public function run() {
        // Crear Admin
        User::updateOrCreate(['email' => 'admin@supercarnes.com'], [
            'name' => 'Administrador Super Carnes',
            'password' => bcrypt('fonda2026'),
            'role' => 'admin'
        ]);

        // Crear Criterios
        $criterios = ['Sabor', 'Presentación', 'Limpieza', 'Originalidad', 'Temperatura'];
        foreach($criterios as $c) {
            Criterio::updateOrCreate(['nombre' => $c]);
        }
    }
}