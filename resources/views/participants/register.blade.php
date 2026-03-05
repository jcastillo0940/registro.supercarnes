<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - {{ $event->nombre }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-slate-100">
<div class="max-w-3xl mx-auto py-10 px-4">
    <div class="bg-white rounded-2xl shadow p-6">
        <h1 class="text-2xl font-bold mb-1">Registro de Participantes</h1>
        <p class="text-slate-600 mb-6">Evento: <strong>{{ $event->nombre }}</strong> ({{ $event->tipo_evento }})</p>

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 p-3 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('participants.store', $event->slug) }}" class="space-y-4" id="participant-form">
            @csrf
            <input type="hidden" id="tipo_evento" value="{{ $event->tipo_evento }}">

            <input name="nombre_persona" value="{{ old('nombre_persona') }}" class="w-full border rounded p-2" placeholder="Nombre del responsable" required>
            <input name="cedula" value="{{ old('cedula') }}" class="w-full border rounded p-2" placeholder="Cédula" required>
            <input name="telefono" value="{{ old('telefono') }}" class="w-full border rounded p-2" placeholder="Teléfono (6XXXXXXX)" required>
            <input name="nombre_fonda" value="{{ old('nombre_fonda') }}" class="w-full border rounded p-2" placeholder="Nombre del participante / equipo" required>
            <input name="ubicacion" value="{{ old('ubicacion') }}" class="w-full border rounded p-2" placeholder="Ubicación" required>
            <input name="plato_preparar" value="{{ old('plato_preparar') }}" class="w-full border rounded p-2" placeholder="Plato o propuesta" required>

            <div id="rock_fields" class="hidden">
                <input name="nombre_banda" value="{{ old('nombre_banda') }}" class="w-full border rounded p-2" placeholder="Nombre de Banda">
            </div>

            <div id="bbq_fields" class="hidden">
                <input name="tipo_corte" value="{{ old('tipo_corte') }}" class="w-full border rounded p-2" placeholder="Tipo de Corte">
            </div>

            <button class="w-full bg-blue-600 text-white py-2 rounded">Registrarme</button>
        </form>
    </div>
</div>

<script>
    const tipo = document.getElementById('tipo_evento').value;
    const rock = document.getElementById('rock_fields');
    const bbq = document.getElementById('bbq_fields');

    if (tipo === 'rock_fest') {
        rock.classList.remove('hidden');
        const input = rock.querySelector('input[name="nombre_banda"]');
        if (input) input.required = true;
    }

    if (tipo === 'bbq_challenge') {
        bbq.classList.remove('hidden');
        const input = bbq.querySelector('input[name="tipo_corte"]');
        if (input) input.required = true;
    }
</script>
</body>
</html>
