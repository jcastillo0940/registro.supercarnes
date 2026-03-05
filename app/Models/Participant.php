<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    protected $table = 'fondas';

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


    protected function casts(): array
    {
        return [
            'datos_extra' => 'array',
        ];
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function evaluaciones()
    {
        return $this->hasMany(Evaluacion::class, 'fonda_id');
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
