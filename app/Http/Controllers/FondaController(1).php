<?php

namespace App\Http\Controllers;

use App\Models\Fonda;
use App\Models\Criterio;
use App\Models\Evaluacion;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class FondaController extends Controller 
{
    /**
     * Registro Público - Pantalla Completa
     */
    public function register() 
    { 
        return view('fonda.register'); 
    }

    /**
     * Procesa la inscripción de la fonda y genera el QR
     */
    public function store(Request $request) 
    {
        // Validación de datos con mensajes personalizados
        $data = $request->validate([
            'nombre_persona' => 'required|string|max:255',
            'cedula'         => 'required|string|min:4|max:20|unique:fondas',
            'telefono'       => ['required', 'regex:/^6[0-9]{7}$/'], 
            'nombre_fonda'   => 'required|string|max:255',
            'ubicacion'      => 'required|string|max:500',
            'plato_preparar' => 'required|string|max:500',
        ], [
            'cedula.unique' => 'Esta cédula ya está registrada en el sistema.',
            'cedula.min' => 'La cédula debe tener al menos 4 caracteres.',
            'telefono.regex' => 'El número debe ser un celular válido de Panamá (8 dígitos empezando con 6).',
        ]);

        // Usar transacción para asegurar integridad de datos
        DB::beginTransaction();
        
        try {
            // Crear la fonda
            $fonda = Fonda::create($data);
            
            // Crear directorio de QRs si no existe (con permisos seguros)
            $qrDirectory = public_path('qrs');
            if (!file_exists($qrDirectory)) {
                mkdir($qrDirectory, 0755, true);
            }
            
            // Generar nombre y ruta del QR
            $qrFileName = 'fonda_' . $fonda->id . '.png';
            $qrPath = 'qrs/' . $qrFileName;
            $qrFullPath = public_path($qrPath);
            
            // Generar QR con mejor configuración
            QrCode::format('png')
                  ->size(300)
                  ->margin(2)
                  ->errorCorrection('H') // Alta corrección de errores
                  ->generate(
                      url('/evaluar/' . $fonda->id), 
                      $qrFullPath
                  );
            
            // Verificar que el archivo se creó correctamente
            if (!file_exists($qrFullPath)) {
                throw new Exception('No se pudo crear el archivo QR');
            }
            
            // Actualizar la ruta del QR en la base de datos
            $fonda->update(['qr_code' => $qrPath]);
            
            // Confirmar la transacción
            DB::commit();
            
            // Retornar vista de éxito
            return view('fonda.success', compact('fonda'));
            
        } catch (Exception $e) {
            // Revertir cambios si algo falla
            DB::rollBack();
            
            // Registrar el error (opcional)
            // Log::error('Error al registrar fonda: ' . $e->getMessage());
            
            // Retornar con mensaje de error
            return back()
                ->withErrors(['error' => 'Ocurrió un error al procesar el registro. Por favor, intente nuevamente.'])
                ->withInput();
        }
    }

    /**
     * Panel Principal del Jurado (Buscador)
     */
    public function panelJurado() 
    {
        // Carga fondas con la relación de evaluaciones filtrada por el usuario actual
        $fondas = Fonda::with(['evaluaciones' => function($query) {
            $query->where('user_id', Auth::id());
        }])
        ->orderBy('nombre_fonda', 'asc')
        ->get();

        return view('jurado.index', compact('fondas'));
    }

    /**
     * Formulario de Evaluación (Bloquea si ya votó)
     */
    public function evaluar(Fonda $fonda) 
    {
        // Validación de voto existente
        $yaVoto = Evaluacion::where('user_id', Auth::id())
                            ->where('fonda_id', $fonda->id)
                            ->exists();

        if ($yaVoto) {
            return redirect()
                ->route('jurado.panel')
                ->with('error', 'Ya has calificado esta fonda anteriormente.');
        }

        // Cargar criterios activos
        $criterios = Criterio::where('activo', true)
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
    public function guardarEvaluacion(Request $request, Fonda $fonda) 
    {
        // Re-validación de seguridad (prevenir doble envío)
        $yaVoto = Evaluacion::where('user_id', Auth::id())
                            ->where('fonda_id', $fonda->id)
                            ->exists();

        if ($yaVoto) {
            return redirect()
                ->route('jurado.panel')
                ->with('error', 'Acción no permitida: Ya calificaste esta fonda.');
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
                                   ->where('activo', true)
                                   ->count();

        if ($criteriosValidos !== count($criteriosIds)) {
            return back()
                ->withErrors(['error' => 'Algunos de los criterios seleccionados no son válidos.'])
                ->withInput();
        }

        // Usar transacción para insertar todas las evaluaciones
        DB::beginTransaction();
        
        try {
            // Insertar cada criterio individualmente
            foreach ($validated['puntos'] as $criterio_id => $puntos) {
                Evaluacion::create([
                    'user_id' => Auth::id(),
                    'fonda_id' => $fonda->id,
                    'criterio_id' => $criterio_id,
                    'puntaje' => $puntos,
                    'notas' => $validated['notas'] ?? null
                ]);
            }
            
            // Confirmar la transacción
            DB::commit();
            
            return redirect()
                ->route('jurado.panel')
                ->with('status', '✅ Evaluación de "' . $fonda->nombre_fonda . '" guardada correctamente.');
                
        } catch (Exception $e) {
            // Revertir si algo falla
            DB::rollBack();
            
            return back()
                ->withErrors(['error' => 'Error al guardar la evaluación. Por favor, intente nuevamente.'])
                ->withInput();
        }
    }
}