<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::orderByDesc('id')->paginate(15);

        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('events', 'slug')],
            'logo' => ['nullable', 'string', 'max:2048'],
            'color_primario' => ['nullable', 'string', 'max:20'],
            'fecha_inicio' => ['nullable', 'date'],
            'fecha_fin' => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
            'tipo_votacion' => ['required', Rule::in(['publico', 'jurado', 'ambos'])],
            'estado' => ['required', Rule::in(['activo', 'inactivo', 'proximamente'])],
        ]);

        Event::create($validated);

        return redirect()->route('admin.events.index')->with('success', 'Evento creado correctamente.');
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('events', 'slug')->ignore($event->id)],
            'logo' => ['nullable', 'string', 'max:2048'],
            'color_primario' => ['nullable', 'string', 'max:20'],
            'fecha_inicio' => ['nullable', 'date'],
            'fecha_fin' => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
            'tipo_votacion' => ['required', Rule::in(['publico', 'jurado', 'ambos'])],
            'estado' => ['required', Rule::in(['activo', 'inactivo', 'proximamente'])],
        ]);

        $event->update($validated);

        return redirect()->route('admin.events.index')->with('success', 'Evento actualizado correctamente.');
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('admin.events.index')->with('success', 'Evento eliminado correctamente.');
    }
}
