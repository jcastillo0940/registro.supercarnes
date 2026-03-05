<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,900;1,900&family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <title>Evaluación Oficial - Super Carnes</title>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-serif { font-family: 'Playfair Display', serif; }
        @keyframes fade-in { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in { animation: fade-in 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen pb-20">
    <!-- Header -->
    <div class="bg-[#002d5a] p-8 text-white text-center rounded-b-[3rem] shadow-2xl relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-blue-400 via-transparent to-transparent"></div>
        <div class="relative">
            <h1 class="text-xs font-black uppercase tracking-[0.4em] text-blue-300 mb-2">Panel de Calificación</h1>
            <p class="font-serif text-3xl italic font-black text-white">Super Carnes <span class="text-blue-400">Challenge</span></p>
        </div>
    </div>

    <div class="max-w-md mx-auto px-6 -mt-8 animate-fade-in">
        <!-- Participant Card -->
        <div class="bg-white rounded-[2.5rem] shadow-xl p-8 mb-10 border border-slate-100 relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 text-6xl opacity-5">🍴</div>
            <div class="text-center relative">
                <h2 class="text-3xl font-black text-[#002d5a] uppercase italic tracking-tighter leading-tight">{{ $participant->nombre_fonda }}</h2>
                <div class="mt-2 inline-flex items-center px-4 py-1 bg-blue-50 text-blue-600 rounded-full text-[10px] font-black uppercase tracking-widest">
                    {{ $participant->plato_preparar }}
                </div>
            </div>
        </div>

        <form action="{{ route('jurado.evaluar.store', $participant->uuid) }}" method="POST" class="space-y-10">
            @csrf
            
            @foreach($criterios as $criterio)
            <div class="bg-white p-8 rounded-[2.5rem] shadow-xl border border-slate-100 group transition-all duration-300 hover:shadow-2xl">
                <label class="block text-center space-y-2 mb-8">
                    <span class="block text-[10px] font-black uppercase tracking-[0.3em] text-blue-500">Criterio de Evaluación</span>
                    <span class="block text-xl font-black text-[#002d5a] uppercase italic">{{ $criterio->nombre }}</span>
                </label>
                
                <div class="grid grid-cols-5 gap-3">
                    @for ($i = 1; $i <= 10; $i++)
                    <label class="relative cursor-pointer group/item">
                        <input type="radio" name="puntos[{{ $criterio->id }}]" value="{{ $i }}" class="peer hidden" required>
                        <div class="aspect-square flex items-center justify-center rounded-2xl bg-slate-50 border-2 border-transparent text-slate-300 font-black text-sm transition-all duration-300 peer-checked:bg-blue-600 peer-checked:text-white peer-checked:shadow-lg peer-checked:shadow-blue-600/30 peer-checked:scale-110 group-hover/item:bg-slate-100">
                            {{ $i }}
                        </div>
                    </label>
                    @endfor
                </div>
                
                <div class="mt-6 flex justify-between text-[10px] font-black uppercase tracking-widest text-slate-300">
                    <span>Insuficiente</span>
                    <span>Excelente</span>
                </div>
            </div>
            @endforeach

            <!-- Notes Section -->
            <div class="bg-white p-8 rounded-[2.5rem] shadow-xl border border-slate-100">
                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4 ml-2">Notas y Observaciones Finales</label>
                <textarea name="notas" rows="4" class="w-full bg-slate-50 border-2 border-transparent rounded-[1.5rem] p-5 font-medium text-slate-700 focus:bg-white focus:border-blue-500 outline-none transition-all placeholder:text-slate-300" placeholder="Escribe tus comentarios técnicos aquí..."></textarea>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full bg-[#002d5a] text-white font-black py-6 rounded-[2rem] shadow-2xl shadow-blue-900/30 uppercase tracking-[0.3em] text-sm transform hover:scale-[1.02] active:scale-95 transition-all duration-300 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Registrar Calificación
            </button>
        </form>
    </div>
</body>
</html>
