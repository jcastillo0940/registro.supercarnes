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
            $eventId = $data['event_id'] ?? Event::where('estado', 'activo')->value('id') ?? Event::value('id');
            if (! $eventId) {
                throw new Exception('No existe un evento configurado para registrar participantes.');
            }

            $data['event_id'] = $eventId;
            $data['uuid'] = (string) Str::uuid();
            $fonda = Participant::create($data);

            $qrDirectory = base_path('public_html/qrs');
            if (! file_exists($qrDirectory)) {
                mkdir($qrDirectory, 0777, true);
                chmod($qrDirectory, 0777);
            }

            $qrFileName = 'fonda_' . $fonda->id . '.png';
            $qrPath = 'qrs/' . $qrFileName;
            $qrFullPath = base_path('public_html/' . $qrPath);
            $qrUrl = url('/evaluar/' . $fonda->uuid);

            $qrGenerator = new QrCodeGenerator();
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
        $user = Auth::user();
        $events = $user->events()->orderBy('nombre')->get(['events.id', 'events.nombre', 'events.slug']);
        $eventIds = $events->pluck('id');

        $fondas = Participant::whereIn('event_id', $eventIds)
            ->with(['event:id,nombre', 'evaluaciones' => function ($query) {
                $query->where('user_id', Auth::id());
            }])
            ->orderBy('nombre_fonda', 'asc')
            ->get(['id', 'uuid', 'event_id', 'nombre_fonda', 'plato_preparar']);

        return view('jurado.index', [
            'initialState' => [
                'events' => $events,
                'participants' => $fondas,
            ],
        ]);
    }

    public function scannerQR()
    {
        return view('jurado.scanner');
    }

    public function evaluar(Participant $fonda)
    {
        $judge = Auth::user();
        if (! $judge->events()->where('events.id', $fonda->event_id)->exists()) {
            abort(403, 'No tienes permiso para evaluar este evento.');
        }

        $yaVoto = Evaluacion::where('user_id', Auth::id())
            ->where('fonda_id', $fonda->id)
            ->exists();

        if ($yaVoto) {
            return redirect()->route('jurado.panel')->with('error', 'Ya has calificado esta fonda anteriormente.');
        }

        $criterios = Criterio::where('activo', true)
            ->where('event_id', $fonda->event_id)
            ->orderBy('nombre', 'asc')
            ->get();

        if ($criterios->isEmpty()) {
            return redirect()->route('jurado.panel')->with('error', 'No hay criterios de evaluación disponibles en este momento.');
        }

        return view('fonda.evaluar', compact('fonda', 'criterios'));
    }
}
