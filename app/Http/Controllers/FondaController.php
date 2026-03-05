<?php

namespace App\Http\Controllers;

use App\Models\Criterio;
use App\Models\Evaluacion;
use App\Models\Event;
use App\Models\Participant;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Generator as QrCodeGenerator;

class FondaController extends Controller
{
    public function register()
    {
        return $this->reactPage('fonda.register');
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
        ]);

        DB::beginTransaction();
        try {
            $eventId = $data['event_id'] ?? Event::where('estado', 'activo')->value('id') ?? Event::value('id');
            if (! $eventId) throw new Exception('No existe un evento configurado para registrar participantes.');

            $data['event_id'] = $eventId;
            $data['uuid'] = (string) Str::uuid();
            $fonda = Participant::create($data);

            $dir = base_path('public_html/qrs');
            if (! file_exists($dir)) { mkdir($dir, 0777, true); chmod($dir, 0777); }
            $path = 'qrs/fonda_' . $fonda->id . '.png';
            $full = base_path('public_html/' . $path);
            (new QrCodeGenerator())->format('png')->size(300)->margin(2)->errorCorrection('H')->generate(url('/evaluar/' . $fonda->uuid), $full);
            if (! file_exists($full)) throw new Exception('No se pudo generar el archivo QR.');

            $fonda->update(['qr_code' => $path]);
            DB::commit();
            return $this->reactPage('fonda.success', ['fonda' => $fonda]);
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al generar el código QR: ' . $e->getMessage()])->withInput();
        }
    }

    public function panelJurado()
    {
        $events = Auth::user()->events()->orderBy('nombre')->get(['events.id', 'events.nombre', 'events.slug']);
        $fondas = Participant::whereIn('event_id', $events->pluck('id'))
            ->with(['event:id,nombre', 'evaluaciones' => fn ($q) => $q->where('user_id', Auth::id())])
            ->orderBy('nombre_fonda')
            ->get(['id', 'uuid', 'event_id', 'nombre_fonda', 'plato_preparar']);

        return $this->reactPage('jurado.index', ['initialState' => ['events' => $events, 'participants' => $fondas]]);
    }

    public function scannerQR()
    {
        return $this->reactPage('jurado.scanner');
    }

    public function evaluar(Participant $fonda)
    {
        if (! Auth::user()->events()->where('events.id', $fonda->event_id)->exists()) abort(403);
        if (Evaluacion::where('user_id', Auth::id())->where('fonda_id', $fonda->id)->exists()) {
            return redirect()->route('jurado.panel')->with('error', 'Ya has calificado esta fonda anteriormente.');
        }

        $criterios = Criterio::where('activo', true)->where('event_id', $fonda->event_id)->orderBy('nombre')->get();
        if ($criterios->isEmpty()) return redirect()->route('jurado.panel')->with('error', 'No hay criterios disponibles.');

        return $this->reactPage('fonda.evaluar', ['fonda' => $fonda, 'criterios' => $criterios]);
    }
}
