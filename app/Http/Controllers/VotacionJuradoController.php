<?php

namespace App\Http\Controllers;

use App\Models\Evaluacion;
use App\Models\Participant;
use App\Models\User;
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

        return view('admin.votaciones_jurado', compact('jurados', 'juradoSeleccionado', 'votaciones'));
    }

    public function consolidado()
    {
        $jurados = User::whereHas('evaluaciones')->get();
        $fondas = Participant::with('evaluaciones')->get();
        $totalCriterios = \App\Models\Criterio::count();

        return view('admin.consolidado', compact('jurados', 'fondas', 'totalCriterios'));
    }

    public function descargarConsolidadoPDF()
    {
        $jurados = User::whereHas('evaluaciones')->get();

        $fondas = Participant::query()
            ->leftJoin('evaluaciones', 'evaluaciones.fonda_id', '=', 'fondas.id')
            ->select('fondas.*', DB::raw('COALESCE(SUM(evaluaciones.puntaje), 0) as total_puntaje'))
            ->groupBy('fondas.id')
            ->orderByDesc('total_puntaje')
            ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.pdf_consolidado', compact('jurados', 'fondas'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('Consolidado_Oficial_Super_Carnes.pdf');
    }
}
