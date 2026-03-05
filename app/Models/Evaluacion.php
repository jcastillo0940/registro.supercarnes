<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model
{
    // Esto obliga a Laravel a buscar 'evaluaciones' en lugar de 'evaluacions'
    protected $table = 'evaluaciones';

    protected $fillable = [
        'user_id', 
        'fonda_id', 
        'criterio_id', 
        'puntaje', 
        'notas'
    ];

    /**
     * Relación con el Jurado (Usuario)
     */
    public function jurado()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relación con la Fonda que fue evaluada
     */
    public function fonda()
    {
        return $this->belongsTo(Fonda::class, 'fonda_id');
    }

    /**
     * Relación con el Criterio (Sabor, Limpieza, etc.)
     * ESTA ES LA FUNCIÓN QUE FALTA Y CAUSA EL ERROR
     */
    public function criterio()
    {
        return $this->belongsTo(Criterio::class, 'criterio_id');
    }
}