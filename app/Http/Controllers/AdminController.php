<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\User;
use App\Services\CsvService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function index()
    {
        $judgeScores = DB::table('evaluaciones')
            ->select('fonda_id', DB::raw('AVG(puntaje) as judge_avg'))
            ->groupBy('fonda_id');

        $fondas = Participant::query()
            ->select('fondas.*', DB::raw('COALESCE(js.judge_avg, 0) as judge_avg'), DB::raw('(COALESCE(js.judge_avg, 0) + fondas.ajuste_admin) as final_score'))
            ->leftJoinSub($judgeScores, 'js', fn ($join) => $join->on('js.fonda_id', '=', 'fondas.id'))
            ->orderByDesc('final_score')
            ->orderBy('fondas.nombre_fonda')
            ->get();

        return view('admin.dashboard', compact('fondas'));
    }

    public function participantes()
    {
        $participantes = Participant::all();
        return view('admin.participantes', compact('participantes'));
    }

    public function usuarios()
    {
        $usuarios = User::all();
        return view('admin.usuarios', compact('usuarios'));
    }

    public function logistica()
    {
        $participantes = Participant::orderBy('orden_visita', 'asc')->get();
        return view('admin.logistica', compact('participantes'));
    }

    public function guardarOrden(Request $request)
    {
        $validated = $request->validate([
            'orden' => 'required|array',
            'orden.*' => 'required|integer|min:1',
        ]);

        foreach ($validated['orden'] as $id => $posicion) {
            Participant::where('id', $id)->update(['orden_visita' => $posicion]);
        }

        return back()->with('success', 'Ruta de visita actualizada correctamente.');
    }

    public function crearUsuario(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', Rule::in(['admin', 'judge'])],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => $validated['role'],
        ]);

        return back()->with('success', 'Usuario creado');
    }

    public function eliminarFonda(Participant $fonda)
    {
        $fonda->delete();
        return back()->with('success', 'Participante eliminado');
    }

    public function ajustarPuntos(Request $request, Participant $fonda)
    {
        $validated = $request->validate([
            'ajuste' => ['required', 'numeric', 'between:-100,100'],
        ]);

        $fonda->update(['ajuste_admin' => $validated['ajuste']]);
        return back()->with('success', 'Ajuste guardado');
    }

    public function exportar(CsvService $csvExporter)
    {
        $judgeScores = DB::table('evaluaciones')
            ->select('fonda_id', DB::raw('AVG(puntaje) as judge_avg'))
            ->groupBy('fonda_id');

        $fondas = Participant::query()
            ->select('fondas.*', DB::raw('COALESCE(js.judge_avg, 0) as judge_avg'), DB::raw('(COALESCE(js.judge_avg, 0) + fondas.ajuste_admin) as final_score'))
            ->leftJoinSub($judgeScores, 'js', fn ($join) => $join->on('js.fonda_id', '=', 'fondas.id'))
            ->orderByDesc('final_score')
            ->orderBy('fondas.nombre_fonda')
            ->get()
            ->map(fn ($row, $idx) => [
                'posicion' => $idx + 1,
                'nombre_fonda' => $row->nombre_fonda,
                'nombre_persona' => $row->nombre_persona,
                'plato_preparar' => $row->plato_preparar,
                'judge_avg' => round((float) $row->judge_avg, 2),
                'ajuste_admin' => (float) $row->ajuste_admin,
                'final_score' => round((float) $row->final_score, 2),
                'public_votes_count' => 0,
            ]);

        return $csvExporter->generate($fondas);
    }

    public function descargarPDF()
    {
        $participantes = Participant::orderBy('orden_visita', 'asc')->get();
        $pdf = Pdf::loadView('admin.pdf_lista', compact('participantes'));

        return $pdf->download('Lista_Oficial_Fonda_Challenge_2026.pdf');
    }
}
