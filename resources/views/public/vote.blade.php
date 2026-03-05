<!DOCTYPE html>
<html lang="es">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Voto Público - {{ $event->nombre }}</title>
</head>
<body class="bg-gray-50 min-h-screen pb-10">
<div class="max-w-md mx-auto px-4 pt-6">
    <div class="bg-white rounded-2xl shadow-xl p-6 mb-6 border-t-4 border-blue-500">
        <h1 class="text-xl font-black text-center">Votación Pública</h1>
        <p class="text-center text-sm text-gray-600">Evento: {{ $event->nombre }}</p>
        <h2 class="text-2xl font-black text-[#004691] text-center mt-2">{{ $participant->nombre_fonda }}</h2>
    </div>

    @if(session('status'))
        <div class="mb-4 bg-green-100 text-green-800 p-3 rounded">{{ session('status') }}</div>
    @endif

    @if($errors->any())
        <div class="mb-4 bg-red-100 text-red-700 p-3 rounded">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form action="{{ route('public.vote.store', $participant->uuid) }}" method="POST" class="space-y-6">
        @csrf
        @foreach($criterios as $criterio)
            <div class="bg-white p-4 rounded-2xl shadow border">
                <label class="font-bold block mb-3">{{ $criterio->nombre }}</label>
                <div class="grid grid-cols-5 gap-2">
                    @for ($i = 1; $i <= 5; $i++)
                        <label>
                            <input type="radio" name="puntos[{{ $criterio->id }}]" value="{{ $i }}" class="peer hidden" required>
                            <div class="text-center py-2 rounded border peer-checked:bg-blue-600 peer-checked:text-white">{{ $i }}</div>
                        </label>
                    @endfor
                </div>
            </div>
        @endforeach

        <button class="w-full bg-blue-700 text-white py-3 rounded-xl font-bold">Enviar voto</button>
    </form>
</div>
</body>
</html>
