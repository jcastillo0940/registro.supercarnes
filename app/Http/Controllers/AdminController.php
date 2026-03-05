<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    public function index()
    {
        $fondas = Participant::with('evaluaciones')->get()->sortByDesc(function ($f) {
            return $f->puntaje_final;
        });

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

    public function exportar()
    {
        $fondas = Participant::all()->sortByDesc(function ($f) {
            return $f->puntaje_final;
        });

        $csvExporter = new \App\Services\CsvService();

        return $csvExporter->generate($fondas);
    }

    public function descargarPDF()
    {
        $participantes = Participant::orderBy('orden_visita', 'asc')->get();
        $pdf = Pdf::loadView('admin.pdf_lista', compact('participantes'));

        return $pdf->download('Lista_Oficial_Fonda_Challenge_2026.pdf');
    }
}
