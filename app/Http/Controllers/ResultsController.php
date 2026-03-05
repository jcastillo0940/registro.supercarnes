<?php

namespace App\Http\Controllers;

use App\Models\Criterio;
use App\Models\Event;
use App\Models\Participant;
use App\Services\CsvService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResultsController extends Controller
{
    public function publicIndex(Request $request)
    {
        return $this->renderResults($request, false);
    }

    public function adminIndex(Request $request)
    {
        return $this->renderResults($request, true);
    }

    public function exportCsv(Request $request, CsvService $csvService)
    {
        [$ranking] = $this->buildRankingData($request);

        return $csvService->generate($ranking, 'resultados_evento_' . now()->format('Ymd_His') . '.csv');
    }

    public function exportPdf(Request $request)
    {
        [$ranking, $event] = $this->buildRankingData($request);

        $pdf = Pdf::loadView('admin.pdf_resultados_evento', [
            'ranking' => $ranking,
            'event' => $event,
        ])->setPaper('a4', 'landscape');

        $eventSlug = $event?->slug ?? 'general';

        return $pdf->download("resultados_finales_{$eventSlug}.pdf");
    }

    private function renderResults(Request $request, bool $isAdmin)
    {
        [$ranking, $event, $events, $criterios] = $this->buildRankingData($request, true);

        return view('results.index', [
            'isAdmin' => $isAdmin,
            'initialState' => [
                'ranking' => $ranking,
                'selectedEventId' => $event?->id,
                'selectedCriterioId' => $request->integer('criterio_id'),
                'events' => $events,
                'criterios' => $criterios,
            ],
        ]);
    }

    private function buildRankingData(Request $request, bool $withContext = false): array
    {
        $selectedEventId = $request->integer('event_id');
        $selectedCriterioId = $request->integer('criterio_id');

        $events = Event::orderBy('nombre')->get(['id', 'nombre', 'slug']);
        $event = $selectedEventId ? $events->firstWhere('id', $selectedEventId) : $events->first();

        $judgeScores = DB::table('evaluaciones')
            ->select('participant_id', DB::raw('AVG(puntaje) as judge_avg'))
            ->when($selectedCriterioId, fn ($q) => $q->where('criterio_id', $selectedCriterioId))
            ->groupBy('participant_id');

        $publicVotes = DB::table('public_votes')
            ->select('participant_id', DB::raw('COUNT(*) as public_votes_count'))
            ->groupBy('participant_id');

        $rankingQuery = Participant::query()
            ->select([
                'participants.id',
                'participants.uuid',
                'participants.nombre_fonda',
                'participants.nombre_persona',
                'participants.plato_preparar',
                'participants.ajuste_admin',
                DB::raw('COALESCE(js.judge_avg, 0) as judge_avg'),
                DB::raw('COALESCE(pv.public_votes_count, 0) as public_votes_count'),
                DB::raw('(COALESCE(js.judge_avg, 0) + participants.ajuste_admin) as final_score'),
            ])
            ->leftJoinSub($judgeScores, 'js', fn ($join) => $join->on('js.participant_id', '=', 'participants.id'))
            ->leftJoinSub($publicVotes, 'pv', fn ($join) => $join->on('pv.participant_id', '=', 'participants.id'))
            ->when($event, fn ($q) => $q->where('participants.event_id', $event->id))
            ->orderByDesc('final_score')
            ->orderBy('participants.nombre_fonda');

        $ranking = $rankingQuery->get()->map(function ($row, $index) {
            return [
                'posicion' => $index + 1,
                'id' => $row->id,
                'uuid' => $row->uuid,
                'nombre_fonda' => $row->nombre_fonda,
                'nombre_persona' => $row->nombre_persona,
                'plato_preparar' => $row->plato_preparar,
                'judge_avg' => round((float) $row->judge_avg, 2),
                'ajuste_admin' => (float) $row->ajuste_admin,
                'final_score' => round((float) $row->final_score, 2),
                'public_votes_count' => (int) $row->public_votes_count,
            ];
        })->values();

        $criterios = collect();
        if ($event) {
            $criterios = Criterio::where('event_id', $event->id)->where('activo', true)->orderBy('nombre')->get(['id', 'nombre']);
        }

        return $withContext
            ? [$ranking, $event, $events, $criterios]
            : [$ranking, $event];
    }
}
