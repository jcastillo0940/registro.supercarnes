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

        return view('participants.register', compact('event'));
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

        if ($event->tipo_evento === 'rock_fest') {
            $rules['nombre_banda'] = ['required', 'string', 'max:255'];
        }

        if ($event->tipo_evento === 'bbq_challenge') {
            $rules['tipo_corte'] = ['required', 'string', 'max:255'];
        }

        $validated = $request->validate($rules, [
            'telefono.regex' => 'El número debe ser un celular válido de Panamá (8 dígitos empezando con 6).',
            'cedula.unique' => 'Esta cédula ya está registrada para este evento.',
        ]);

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
                'datos_extra' => [
                    'nombre_banda' => $validated['nombre_banda'] ?? null,
                    'tipo_corte' => $validated['tipo_corte'] ?? null,
                ],
            ]);

            $qrDirectory = base_path('public_html/qrs');
            if (! file_exists($qrDirectory)) {
                mkdir($qrDirectory, 0777, true);
                chmod($qrDirectory, 0777);
            }

            $qrFileName = 'participant_' . $participant->uuid . '.png';
            $qrPath = 'qrs/' . $qrFileName;
            $qrFullPath = base_path('public_html/' . $qrPath);
            $qrUrl = url('/evaluar/' . $participant->uuid);

            $qrGenerator = new QrCodeGenerator();
            while (ob_get_level()) {
                ob_end_clean();
            }

            $qrGenerator->format('png')
                ->size(300)
                ->margin(2)
                ->errorCorrection('H')
                ->generate($qrUrl, $qrFullPath);

            if (! file_exists($qrFullPath)) {
                throw new Exception('No se pudo generar el QR del participante.');
            }

            $participant->update(['qr_code' => $qrPath]);
            DB::commit();

            return view('fonda.success', ['fonda' => $participant]);
        } catch (Exception $e) {
            DB::rollBack();

            return back()->withErrors(['error' => 'Error al registrar participante: ' . $e->getMessage()])->withInput();
        }
    }
}
