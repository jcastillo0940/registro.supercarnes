<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fonda extends Model {
    // Definir la tabla explícitamente
    protected $table = 'fondas';

    protected $fillable = [
        'nombre_persona', 'cedula', 'telefono', 'nombre_fonda', 
        'ubicacion', 'plato_preparar', 'qr_code', 'ajuste_admin'
    ];
    
    public function evaluaciones() { 
        return $this->hasMany(Evaluacion::class, 'fonda_id'); 
    }

    // Accesor para el promedio de los jueces
    public function getPromedioAttribute() {
        return round($this->evaluaciones()->avg('puntaje') ?? 0, 2);
    }

    // Accesor para el puntaje final (Promedio + Ajuste manual)
    public function getPuntajeFinalAttribute() {
        return $this->promedio + (float)$this->ajuste_admin;
    }
}