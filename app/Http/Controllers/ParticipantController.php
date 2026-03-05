<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Participant;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use SimpleSoftwareIO\QrCode\Generator as QrCodeGenerator;

class ParticipantController extends Controller
{
    public function create(string $slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();
        return $this->reactPage('participants.register', ['event' => $event]);
    }

    public function store(Request $request, string $slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();
        $rules = [
            'nombre_persona' => ['required', 'string', 'max:255'],
            'cedula' => ['required', 'string', 'min:4', 'max:20', Rule::unique('fondas', 'cedula')->where(fn ($q) => $q->where('event_id', $event->id))],
            'telefono' => ['required', 'regex:/^6[0-9]{7}$/'],
            'nombre_fonda' => ['required', 'string', 'max:255'],
            'ubicacion' => ['required', 'string', 'max:500'],
            'plato_preparar' => ['required', 'string', 'max:500'],
            'nombre_banda' => ['nullable', 'string', 'max:255'],
            'tipo_corte' => ['nullable', 'string', 'max:255'],
        ];
        if ($event->tipo_evento === 'rock_fest') $rules['nombre_banda'] = ['required', 'string', 'max:255'];
        if ($event->tipo_evento === 'bbq_challenge') $rules['tipo_corte'] = ['required', 'string', 'max:255'];

        $validated = $request->validate($rules);

        DB::beginTransaction();
        try {
            $participant = Participant::create([
                'event_id' => $event->id,
                'uuid' => (string) Str::uuid(),
                'nombre_persona' => $validated['nombre_persona'],
                'cedula' => $validated['cedula'],
                'telefono' => $validated['telefono'],
                'nombre_fonda' => $validated['nombre_fonda'],
                'ubicacion' => $validated['ubicacion'],
                'plato_preparar' => $validated['plato_preparar'],
                'datos_extra' => ['nombre_banda' => $validated['nombre_banda'] ?? null, 'tipo_corte' => $validated['tipo_corte'] ?? null],
            ]);

            $dir = base_path('public_html/qrs');
            if (! file_exists($dir)) { mkdir($dir, 0777, true); chmod($dir, 0777); }
            $path = 'qrs/participant_' . $participant->uuid . '.png';
            (new QrCodeGenerator())->format('png')->size(300)->margin(2)->errorCorrection('H')->generate(url('/evaluar/' . $participant->uuid), base_path('public_html/' . $path));
            $participant->update(['qr_code' => $path]);
            DB::commit();

            return $this->reactPage('fonda.success', ['fonda' => $participant]);
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al registrar participante: ' . $e->getMessage()])->withInput();
        }
    }
}
