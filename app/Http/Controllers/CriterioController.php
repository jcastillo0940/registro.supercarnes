<?php

namespace App\Http\Controllers;

use App\Models\Criterio;
use App\Models\Event;
use Illuminate\Http\Request;

class CriterioController extends Controller
{
    public function index(Request $request)
    {
        $events = Event::orderByDesc('id')->get();
        $eventId = $request->integer('event_id') ?: $events->first()?->id;
        $criterios = Criterio::when($eventId, fn ($q) => $q->where('event_id', $eventId))->get();

        return $this->reactPage('admin.criterios', compact('criterios', 'events', 'eventId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'event_id' => ['required', 'exists:events,id'],
            'activo' => ['nullable', 'boolean'],
        ]);

        Criterio::create([
            'nombre' => $validated['nombre'],
            'event_id' => $validated['event_id'],
            'activo' => $validated['activo'] ?? true,
        ]);

        return back();
    }

    public function destroy(Criterio $criterio)
    {
        $criterio->delete();

        return back();
    }
}
