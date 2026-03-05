<?php

namespace App\Http\Controllers;

use App\Models\Criterio;
use App\Models\Evaluacion;
use App\Models\Event;
use App\Models\Participant;
use App\Models\PublicVote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EvaluationController extends Controller
{
    public function panelJurado()
    {
        $user = Auth::user();
        $events = $user->events()->get(['events.id', 'events.nombre']);
        
        $participants = Participant::whereIn('event_id', $events->pluck('id'))
            ->with(['evaluaciones' => function($query) {
                $query->where('user_id', Auth::id());
            }])
            ->orderBy('nombre_fonda', 'asc')
            ->get();

        return view('jurado.index', [
            'initialState' => [
                'events' => $events,
                'participants' => $participants,
            ]
        ]);
    }

    public function scannerQR()
    {
        return view('jurado.scanner');
    }

    public function evaluar(Participant $participant) 
    {
        $judge = Auth::user();
        if (! $judge->events()->where('events.id', $participant->event_id)->exists()) {
            abort(403, 'No tienes permiso para evaluar este evento.');
        }

        $criterios = Criterio::where('activo', true)
                             ->where('event_id', $participant->event_id)
                             ->orderBy('nombre', 'asc')
                             ->get();
        
        if ($criterios->isEmpty()) {
            return redirect()
                ->route('jurado.panel')
                ->with('error', 'No hay criterios de evaluación disponibles en este momento.');
        }

        return view('jurado.evaluar', compact('participant', 'criterios'));
    }

    public function storeJudge(Request $request, Participant $participant)
    {
        $judge = Auth::user();
        if (! $judge->events()->where('events.id', $participant->event_id)->exists()) {
            abort(403, 'No tienes permiso para evaluar este evento.');
        }

        $yaVoto = Evaluacion::where('user_id', Auth::id())->where('participant_id', $participant->id)->exists();
        if ($yaVoto) {
            return redirect()->route('jurado.panel')->with('error', 'Acción no permitida: Ya calificaste este participante.');
        }

        $validated = $request->validate([
            'puntos' => 'required|array|min:1',
            'puntos.*' => 'required|integer|min:0|max:10',
            'notas' => 'nullable|string|max:1000',
        ]);

        $criteriosIds = array_keys($validated['puntos']);
        $criteriosValidos = Criterio::whereIn('id', $criteriosIds)
            ->where('event_id', $participant->event_id)
            ->where('activo', true)
            ->count();

        if ($criteriosValidos !== count($criteriosIds)) {
            return back()->withErrors(['error' => 'Algunos criterios no son válidos.'])->withInput();
        }

        DB::transaction(function () use ($validated, $participant) {
            foreach ($validated['puntos'] as $criterio_id => $puntos) {
                Evaluacion::create([
                    'user_id' => Auth::id(),
                    'participant_id' => $participant->id,
                    'criterio_id' => $criterio_id,
                    'puntaje' => $puntos,
                    'notas' => $validated['notas'] ?? null,
                ]);
            }
        });

        return redirect()->route('jurado.panel')->with('status', '✅ Evaluación guardada correctamente.');
    }

    public function publicForm(Participant $participant)
    {
        $event = Event::findOrFail($participant->event_id);
        if (! $event->public_voting_enabled) {
            abort(403, 'La votación pública está deshabilitada para este evento.');
        }

        $criterios = Criterio::where('event_id', $event->id)->where('activo', true)->orderBy('nombre')->get();

        return view('public.vote', compact('participant', 'event', 'criterios'));
    }

    public function storePublic(Request $request, Participant $participant)
    {
        $event = Event::findOrFail($participant->event_id);
        if (! $event->public_voting_enabled) {
            abort(403, 'La votación pública está deshabilitada para este evento.');
        }

        $validated = $request->validate([
            'puntos' => 'required|array|min:1',
            'puntos.*' => 'required|integer|min:1|max:5',
        ]);

        $fingerprint = hash('sha256', ($request->ip() ?? '0.0.0.0') . '|' . substr((string) $request->userAgent(), 0, 255));

        $yaVoto = PublicVote::where('event_id', $event->id)
            ->where('participant_id', $participant->id)
            ->where('voter_fingerprint', $fingerprint)
            ->exists();

        if ($yaVoto) {
            return back()->withErrors(['error' => 'Ya registraste un voto para este participante.']);
        }

        $criteriosIds = array_keys($validated['puntos']);
        $criteriosValidos = Criterio::whereIn('id', $criteriosIds)
            ->where('event_id', $event->id)
            ->where('activo', true)
            ->count();

        if ($criteriosValidos !== count($criteriosIds)) {
            return back()->withErrors(['error' => 'Criterios no válidos.']);
        }

        PublicVote::create([
            'event_id' => $event->id,
            'participant_id' => $participant->id,
            'voter_fingerprint' => $fingerprint,
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 255),
            'scores' => $validated['puntos'],
        ]);

        return back()->with('status', '✅ Voto público registrado.');
    }
}
