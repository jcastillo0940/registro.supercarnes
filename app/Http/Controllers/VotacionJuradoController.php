<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Fonda;
use App\Models\Evaluacion;
use Illuminate\Http\Request;

class VotacionJuradoController extends Controller
{
    /**
     * Muestra el listado de jurados y sus votaciones detalladas
     */
    public function index(Request $request)
    {
        // Obtenemos todos los usuarios que tienen al menos una evaluación (los jurados)
        $jurados = User::whereHas('evaluaciones')->get();

        // Si se seleccionó un jurado específico, cargamos sus votos
        $juradoSeleccionado = null;
        $votaciones = [];

        if ($request->has('jurado_id')) {
            $juradoSeleccionado = User::findOrFail($request->jurado_id);
            
            // Agrupamos las evaluaciones por fonda para que sea fácil de leer
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
            $fondas = Fonda::with('evaluaciones')->get();
            
            // Obtenemos los criterios para saber cuántos hay y calcular promedios si fuera necesario
            $totalCriterios = \App\Models\Criterio::count();
        
            return view('admin.consolidado', compact('jurados', 'fondas', 'totalCriterios'));
        }
        public function descargarConsolidadoPDF()
        {
            $jurados = User::whereHas('evaluaciones')->get();
            
            // Obtenemos las fondas y las ordenamos por la suma de sus puntajes de mayor a menor
            $fondas = Fonda::with('evaluaciones')
                ->get()
                ->sortByDesc(function($fonda) {
                    return $fonda->evaluaciones->sum('puntaje');
                });
        
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.pdf_consolidado', compact('jurados', 'fondas'))
                      ->setPaper('a4', 'landscape'); 
        
            return $pdf->stream('Consolidado_Oficial_Super_Carnes.pdf');
        }
}