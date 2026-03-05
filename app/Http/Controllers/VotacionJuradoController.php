<?php

namespace App\Http\Controllers;

use App\Models\Evaluacion;
use App\Models\Participant;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VotacionJuradoController extends Controller
{
    public function index(Request $request)
    {
        $jurados = User::whereHas('evaluaciones')->get();
        $juradoSeleccionado = null;
        $votaciones = [];

        if ($request->has('jurado_id')) {
            $juradoSeleccionado = User::findOrFail($request->jurado_id);
            $votaciones = Evaluacion::where('user_id', $request->jurado_id)
                ->with(['fonda', 'criterio'])
                ->get()
                ->groupBy('fonda_id');
        }

        return $this->reactPage('admin.votaciones_jurado', compact('jurados', 'juradoSeleccionado', 'votaciones'));
    }

    public function consolidado()
    {
        $jurados = User::whereHas('evaluaciones')->get();
        $fondas = Participant::with('evaluaciones')->get();
        $totalCriterios = \App\Models\Criterio::count();

        return $this->reactPage('admin.consolidado', compact('jurados', 'fondas', 'totalCriterios'));
    }

    public function descargarConsolidadoPDF()
    {
        $fondas = Participant::query()
            ->leftJoin('evaluaciones', 'evaluaciones.fonda_id', '=', 'fondas.id')
            ->select('fondas.*', DB::raw('COALESCE(SUM(evaluaciones.puntaje),0) as total_puntaje'))
            ->groupBy('fondas.id')
            ->orderByDesc('total_puntaje')
            ->get();

        $html = '<h2>Consolidado</h2><table border="1" cellspacing="0" cellpadding="4"><tr><th>#</th><th>Participante</th><th>Total</th></tr>';
        foreach ($fondas as $i => $f) {
            $html .= '<tr><td>' . ($i + 1) . '</td><td>' . e($f->nombre_fonda) . '</td><td>' . e($f->total_puntaje) . '</td></tr>';
        }
        $html .= '</table>';

        return Pdf::loadHTML($html)->setPaper('a4', 'landscape')->stream('Consolidado_Oficial_Super_Carnes.pdf');
    }
}
