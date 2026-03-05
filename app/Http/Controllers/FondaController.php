<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Criterio;
use App\Models\Event;
use App\Models\Evaluacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Generator as QrCodeGenerator;
use Exception;

class FondaController extends Controller
{
    public function register()
    {
        return view('fonda.register');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre_persona' => 'required|string|max:255',
            'cedula' => 'required|string|min:4|max:20|unique:fondas',
            'telefono' => ['required', 'regex:/^6[0-9]{7}$/'],
            'nombre_fonda' => 'required|string|max:255',
            'ubicacion' => 'required|string|max:500',
            'plato_preparar' => 'required|string|max:500',
            'event_id' => 'nullable|exists:events,id',
        ], [
            'cedula.unique' => 'Esta cédula ya está registrada en el sistema.',
            'cedula.min' => 'La cédula debe tener al menos 4 caracteres.',
            'telefono.regex' => 'El número debe ser un celular válido de Panamá (8 dígitos empezando con 6).',
        ]);

        DB::beginTransaction();

        try {
            // Crear la fonda
            $eventId = $data['event_id'] ?? Event::where('estado', 'activo')->value('id') ?? Event::value('id');
            
            if (! $eventId) {
                throw new Exception('No existe un evento configurado para registrar participantes.');
            }

            $data['event_id'] = $eventId;
            $fonda = Participant::create($data);
            
            // Crear directorio de QRs si no existe
            // IMPORTANTE: En Hostinger, public_html es el directorio público, no public
            $qrDirectory = base_path('public_html/qrs');
            if (! file_exists($qrDirectory)) {
                mkdir($qrDirectory, 0777, true);
                chmod($qrDirectory, 0777);
            }

            $qrFileName = 'fonda_' . $fonda->id . '.png';
            $qrPath = 'qrs/' . $qrFileName;
            $qrFullPath = base_path('public_html/' . $qrPath);
            
            // URL que contendrá el QR
            $qrUrl = url('/evaluar/' . $fonda->uuid);
            
            // SOLUCIÓN: Usar instancia directa (igual que el test exitoso)
            $qrGenerator = new QrCodeGenerator;
            
            // Limpiar cualquier salida previa
            while (ob_get_level()) {
                ob_end_clean();
            }

            $qrGenerator->format('png')->size(300)->margin(2)->errorCorrection('H')->generate($qrUrl, $qrFullPath);

            if (! file_exists($qrFullPath)) {
                throw new Exception('No se pudo generar el archivo QR en: ' . $qrFullPath);
            }

            if (filesize($qrFullPath) < 100) {
                @unlink($qrFullPath);
                throw new Exception('El QR generado está corrupto.');
            }

            @chmod($qrFullPath, 0644);
            $fonda->update(['qr_code' => $qrPath]);
            DB::commit();

            return view('fonda.success', compact('fonda'));
        } catch (Exception $e) {
            DB::rollBack();
            if (isset($qrFullPath) && file_exists($qrFullPath)) {
                @unlink($qrFullPath);
            }

            return back()->withErrors(['error' => 'Error al generar el código QR: ' . $e->getMessage()])->withInput();
        }
    }

    public function panelJurado()
    {
        // Carga fondas con la relación de evaluaciones filtrada por el usuario actual
        $fondas = Participant::with(['evaluaciones' => function($query) {
            $query->where('user_id', Auth::id());
        }])
        ->orderBy('nombre_fonda', 'asc')
        ->get();

        return view('jurado.index', compact('fondas'));
    }

    public function scannerQR()
    {
        return view('jurado.scanner');
    }

    /**
     * Formulario de Evaluación (Bloquea si ya votó)
     */
    public function evaluar(Participant $fonda) 
    {
        $judge = Auth::user();
        if (! $judge->events()->where('events.id', $fonda->event_id)->exists()) {
            abort(403, 'No tienes permiso para evaluar este evento.');
        }

        // Cargar criterios activos
        $criterios = Criterio::where('activo', true)
                             ->where('event_id', $fonda->event_id)
                             ->orderBy('nombre', 'asc')
                             ->get();
        
        // Verificar que haya criterios disponibles
        if ($criterios->isEmpty()) {
            return redirect()
                ->route('jurado.panel')
                ->with('error', 'No hay criterios de evaluación disponibles en este momento.');
        }

        return view('fonda.evaluar', compact('fonda', 'criterios'));
    }

    /**
     * Guarda la evaluación de múltiples criterios
     */
    public function guardarEvaluacion(Request $request, Participant $fonda) 
    {
        // Re-validación de seguridad (prevenir doble envío)
        $yaVoto = Evaluacion::where('user_id', Auth::id())
            ->where('fonda_id', $fonda->id)
            ->exists();

        if ($yaVoto) {
            return redirect()->route('jurado.panel')->with('error', 'Ya has calificado esta fonda anteriormente.');
        }

        // Validación de datos con rangos de puntaje
        $validated = $request->validate([
            'puntos' => 'required|array|min:1',
            'puntos.*' => 'required|integer|min:0|max:10',
            'notas' => 'nullable|string|max:1000'
        ], [
            'puntos.required' => 'Debes calificar al menos un criterio.',
            'puntos.*.required' => 'Todos los criterios deben tener una calificación.',
            'puntos.*.integer' => 'La calificación debe ser un número entero.',
            'puntos.*.min' => 'El puntaje mínimo es 0.',
            'puntos.*.max' => 'El puntaje máximo es 10.',
            'notas.max' => 'Las notas no pueden exceder 1000 caracteres.',
        ]);

        // Verificar que los criterios existan y estén activos
        $criteriosIds = array_keys($validated['puntos']);
        $criteriosValidos = Criterio::whereIn('id', $criteriosIds)
                                   ->where('event_id', $fonda->event_id)
                                   ->where('activo', true)
                                   ->count();

        if ($criterios->isEmpty()) {
            return redirect()->route('jurado.panel')->with('error', 'No hay criterios de evaluación disponibles en este momento.');
        }

        return view('fonda.evaluar', compact('fonda', 'criterios'));
    }
}
