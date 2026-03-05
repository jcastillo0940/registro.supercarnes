<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Participant;
use App\Models\Evaluacion;
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
                ->with(['participant', 'criterio'])
                ->get()
                ->groupBy('participant_id');
        }

        return view('admin.votaciones_jurado', compact('jurados', 'juradoSeleccionado', 'votaciones'));
    }

    public function consolidado()
        {
            $jurados = User::whereHas('evaluaciones')->get();
            $participants = Participant::with('evaluaciones')->get();
            
            // Obtenemos los criterios para saber cuántos hay y calcular promedios si fuera necesario
            $totalCriterios = \App\Models\Criterio::count();
        
            return view('admin.consolidado', compact('jurados', 'participants', 'totalCriterios'));
        }
        public function descargarConsolidadoPDF()
        {
            $jurados = User::whereHas('evaluaciones')->get();
            
            // Obtenemos las participaciones y las ordenamos por la suma de sus puntajes de mayor a menor
            $participants = Participant::with('evaluaciones')
                ->get()
                ->sortByDesc(function($p) {
                    return $p->evaluaciones->sum('puntaje');
                });
        
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.pdf_consolidado', compact('jurados', 'participants'))
                      ->setPaper('a4', 'landscape'); 
        
            return $pdf->stream('Consolidado_Oficial_Super_Carnes.pdf');
        }
}
