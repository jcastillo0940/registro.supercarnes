<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    // Usará el nombre por defecto 'participants' después de la migración.

    protected $fillable = [
        'event_id',
        'uuid',
        'nombre_persona',
        'cedula',
        'telefono',
        'nombre_fonda',
        'ubicacion',
        'plato_preparar',
        'datos_extra',
        'qr_code',
        'ajuste_admin',
        'orden_visita',
    ];

    /**
     * Usar el UUID para el enlace de evaluación.
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    protected $casts = [
        'datos_extra' => 'array',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function evaluaciones()
    {
        return $this->hasMany(Evaluacion::class, 'participant_id');
    }

    public function getPromedioAttribute()
    {
        return round($this->evaluaciones()->avg('puntaje') ?? 0, 2);
    }

    public function getPuntajeFinalAttribute()
    {
        return $this->promedio + (float) $this->ajuste_admin;
    }
}
