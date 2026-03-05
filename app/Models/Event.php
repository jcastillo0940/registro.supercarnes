<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'nombre',
        'slug',
        'tipo_evento',
        'logo',
        'color_primario',
        'color_secundario',
        'fecha_inicio',
        'fecha_fin',
        'tipo_votacion',
        'estado',
    ];

    protected $appends = ['logo_url'];

    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'date',
            'fecha_fin' => 'date',
        ];
    }

    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo ? asset('storage/' . $this->logo) : null;
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
