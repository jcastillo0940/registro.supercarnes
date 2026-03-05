<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
            'tipo_evento' => ['required', Rule::in(['general', 'rock_fest', 'bbq_challenge'])],
            'logo' => ['nullable', 'image', 'max:2048'],
            'color_primario' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'color_secundario' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'fecha_inicio' => ['nullable', 'date'],
            'fecha_fin' => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
            'tipo_votacion' => ['required', Rule::in(['publico', 'jurado', 'ambos'])],
            'estado' => ['required', Rule::in(['activo', 'inactivo', 'proximamente'])],
            'public_voting_enabled' => ['nullable', 'boolean'],
        ]);

        $validated['public_voting_enabled'] = (bool) ($validated['public_voting_enabled'] ?? false);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('events', 'public');
        }

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
            'tipo_evento' => ['required', Rule::in(['general', 'rock_fest', 'bbq_challenge'])],
            'logo' => ['nullable', 'image', 'max:2048'],
            'color_primario' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'color_secundario' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'fecha_inicio' => ['nullable', 'date'],
            'fecha_fin' => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
            'tipo_votacion' => ['required', Rule::in(['publico', 'jurado', 'ambos'])],
            'estado' => ['required', Rule::in(['activo', 'inactivo', 'proximamente'])],
            'public_voting_enabled' => ['nullable', 'boolean'],
        ]);

        $validated['public_voting_enabled'] = (bool) ($validated['public_voting_enabled'] ?? false);

        if ($request->hasFile('logo')) {
            if ($event->logo) {
                Storage::disk('public')->delete($event->logo);
            }
            $validated['logo'] = $request->file('logo')->store('events', 'public');
        }

        $event->update($validated);

        return redirect()->route('admin.events.index')->with('success', 'Evento actualizado correctamente.');
    }

    public function destroy(Event $event)
    {
        if ($event->logo) {
            Storage::disk('public')->delete($event->logo);
        }

        $event->delete();

        return redirect()->route('admin.events.index')->with('success', 'Evento eliminado correctamente.');
    }
}
