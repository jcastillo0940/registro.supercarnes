<?php
namespace App\Http\Controllers;

use App\Models\Fonda;
use App\Models\User;
use App\Models\Criterio;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; // Importación para el PDF

class AdminController extends Controller {
    
    public function index() {
        $fondas = Fonda::with('evaluaciones')->get()->sortByDesc(function($f) { 
            return $f->puntaje_final; 
        });
        return view('admin.dashboard', compact('fondas'));
    }

    public function participantes() {
        $participantes = Fonda::all();
        return view('admin.participantes', compact('participantes'));
    }

    public function usuarios() {
        $usuarios = User::all();
        return view('admin.usuarios', compact('usuarios'));
    }

    // --- NUEVO: Gestión de Logística ---
    public function logistica() {
        $participantes = Fonda::orderBy('orden_visita', 'asc')->get();
        return view('admin.logistica', compact('participantes'));
    }

    public function guardarOrden(Request $request) {
        $request->validate([
            'orden' => 'required|array'
        ]);

        foreach ($request->orden as $id => $posicion) {
            Fonda::where('id', $id)->update(['orden_visita' => $posicion]);
        }

        return back()->with('success', 'Ruta de visita actualizada correctamente.');
    }

    // --- Gestión de Usuarios ---
    public function crearUsuario(Request $request) {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);
        return back()->with('success', 'Usuario creado');
    }

    // --- Gestión de Fondas ---
    public function eliminarFonda(Fonda $fonda) {
        $fonda->delete();
        return back()->with('success', 'Fonda eliminada');
    }

    public function ajustarPuntos(Request $request, Fonda $fonda) {
        $fonda->update(['ajuste_admin' => $request->ajuste]);
        return back()->with('success', 'Ajuste guardado');
    }

    // --- Reportes ---
    public function exportar() {
        $fondas = Fonda::all()->sortByDesc(function($f) { return $f->puntaje_final; });
        $csvExporter = new \App\Services\CsvService();
        return $csvExporter->generate($fondas);
    }
    
    public function descargarPDF() {
        $participantes = Fonda::orderBy('orden_visita', 'asc')->get();
        // Asegúrate de que la vista 'admin.pdf_lista' exista
        $pdf = Pdf::loadView('admin.pdf_lista', compact('participantes'));
        return $pdf->download('Lista_Oficial_Fonda_Challenge_2026.pdf');
    }
}