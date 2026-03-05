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
    public function storeJudge(Request $request, Participant $fonda)
    {
        if (! Auth::user()->events()->where('events.id', $fonda->event_id)->exists()) abort(403);
        if (Evaluacion::where('user_id', Auth::id())->where('fonda_id', $fonda->id)->exists()) {
            return redirect()->route('jurado.panel')->with('error', 'Acción no permitida: Ya calificaste esta fonda.');
        }

        $validated = $request->validate(['puntos' => 'required|array|min:1', 'puntos.*' => 'required|integer|min:0|max:10', 'notas' => 'nullable|string|max:1000']);
        $ids = array_keys($validated['puntos']);
        $count = Criterio::whereIn('id', $ids)->where('event_id', $fonda->event_id)->where('activo', true)->count();
        if ($count !== count($ids)) return back()->withErrors(['error' => 'Algunos criterios no son válidos.'])->withInput();

        DB::transaction(function () use ($validated, $fonda) {
            foreach ($validated['puntos'] as $criterio_id => $puntos) {
                Evaluacion::create(['user_id' => Auth::id(), 'fonda_id' => $fonda->id, 'criterio_id' => $criterio_id, 'puntaje' => $puntos, 'notas' => $validated['notas'] ?? null]);
            }
        });

        return redirect()->route('jurado.panel')->with('status', '✅ Evaluación guardada correctamente.');
    }

    public function publicForm(Participant $participant)
    {
        $event = Event::findOrFail($participant->event_id);
        if (! $event->public_voting_enabled) abort(403);
        $criterios = Criterio::where('event_id', $event->id)->where('activo', true)->orderBy('nombre')->get();
        return $this->reactPage('public.vote', ['participant' => $participant, 'event' => $event, 'criterios' => $criterios]);
    }

    public function storePublic(Request $request, Participant $participant)
    {
        $event = Event::findOrFail($participant->event_id);
        if (! $event->public_voting_enabled) abort(403);
        $validated = $request->validate(['puntos' => 'required|array|min:1', 'puntos.*' => 'required|integer|min:1|max:5']);
        $fingerprint = hash('sha256', ($request->ip() ?? '0.0.0.0') . '|' . substr((string) $request->userAgent(), 0, 255));

        if (PublicVote::where('event_id', $event->id)->where('participant_id', $participant->id)->where('voter_fingerprint', $fingerprint)->exists()) {
            return back()->withErrors(['error' => 'Ya registraste un voto para este participante.']);
        }

        $ids = array_keys($validated['puntos']);
        if (Criterio::whereIn('id', $ids)->where('event_id', $event->id)->where('activo', true)->count() !== count($ids)) {
            return back()->withErrors(['error' => 'Criterios no válidos.']);
        }

        PublicVote::create(['event_id' => $event->id, 'participant_id' => $participant->id, 'voter_fingerprint' => $fingerprint, 'ip_address' => $request->ip(), 'user_agent' => substr((string) $request->userAgent(), 0, 255), 'scores' => $validated['puntos']]);
        return back()->with('status', '✅ Voto público registrado.');
    }
}
