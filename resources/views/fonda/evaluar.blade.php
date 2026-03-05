
<!DOCTYPE html>
<html lang="es">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Evaluar Fonda - Super Carnes</title>
</head>
<body class="bg-gray-50 min-h-screen pb-10">
    <div class="bg-[#004691] p-6 text-white text-center rounded-b-3xl shadow-lg">
        <h1 class="text-xl font-bold uppercase">Evaluación Jurado</h1>
        <p class="text-yellow-400 font-bold mt-1 tracking-widest">FONDA CHALLENGE 2026</p>
    </div>

    <div class="max-w-md mx-auto px-4 -mt-6">
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-6 border-t-4 border-yellow-400">
            <h2 class="text-2xl font-black text-[#004691] text-center">{{ $fonda->nombre_fonda }}</h2>
            <p class="text-center text-gray-500 italic mt-1 font-medium">Plato: {{ $fonda->plato_preparar }}</p>
        </div>

        <form action="{{ route('jurado.evaluar.store', $fonda->uuid) }}" method="POST" class="space-y-8">
            @csrf
            @foreach($criterios as $criterio)
            <div class="bg-white p-5 rounded-2xl shadow-md border border-gray-100">
                <label class="block text-lg font-bold text-gray-700 mb-4 text-center">
                    {{ $criterio->nombre }}
                </label>
                <div class="flex justify-between items-center space-x-2">
                    @for ($i = 1; $i <= 5; $i++)
                    <label class="flex-1">
                        <input type="radio" name="puntos[{{ $criterio->id }}]" value="{{ $i }}" class="peer hidden" required>
                        <div class="text-center py-3 rounded-xl border-2 border-gray-100 peer-checked:border-[#FFD100] peer-checked:bg-yellow-50 peer-checked:text-[#004691] font-black text-xl transition-all text-gray-400">
                            {{ $i }}
                        </div>
                    </label>
                    @endfor
                </div>
            </div>
            @endforeach

            <div class="bg-white p-5 rounded-2xl shadow-md">
                <label class="block font-bold text-gray-700 mb-2 uppercase text-xs">Notas y Observaciones</label>
                <textarea name="notas" rows="3" class="w-full border-2 border-gray-100 rounded-xl p-3 focus:border-[#FFD100] outline-none" placeholder="Escribe aquí tus comentarios..."></textarea>
            </div>

            <button type="submit" class="w-full bg-[#004691] text-white font-black py-5 rounded-2xl shadow-2xl uppercase tracking-widest text-lg active:scale-95 transition-transform">
                Enviar Calificación
            </button>
        </form>
    </div>
</body>
</html>
