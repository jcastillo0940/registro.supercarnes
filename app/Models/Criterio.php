<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Criterio extends Model
{
    protected $fillable = ['event_id', 'nombre', 'activo'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
