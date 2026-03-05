<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'nombre',
        'slug',
        'logo',
        'color_primario',
        'fecha_inicio',
        'fecha_fin',
        'tipo_votacion',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'date',
            'fecha_fin' => 'date',
        ];
    }

    public function participants()
    {
        return $this->hasMany(Participant::class, 'event_id');
    }

    public function judges()
    {
        return $this->belongsToMany(User::class, 'event_user')
            ->withTimestamps();
    }
}
