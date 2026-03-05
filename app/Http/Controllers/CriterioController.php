<?php
namespace App\Http\Controllers;
use App\Models\Criterio;
use Illuminate\Http\Request;

class CriterioController extends Controller {
    public function index() { $criterios = Criterio::all(); return view('admin.criterios', compact('criterios')); }
    public function store(Request $request) { Criterio::create($request->all()); return back(); }
    public function destroy(Criterio $criterio) { $criterio->delete(); return back(); }
}