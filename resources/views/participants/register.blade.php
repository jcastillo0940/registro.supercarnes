<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,900;1,900&family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <title>Inscripción Oficial - {{ $event->nombre }}</title>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-serif { font-family: 'Playfair Display', serif; }
        @keyframes fade-up { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-up { animation: fade-up 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>
</head>
<body class="bg-slate-900 min-h-screen flex items-center justify-center p-4 sm:p-10 relative overflow-hidden">
    <!-- Sophisticated Background Elements -->
    <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-blue-500 rounded-full blur-[120px]"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-blue-700 rounded-full blur-[120px]"></div>
    </div>

    <div class="w-full max-w-2xl bg-white rounded-[3rem] shadow-2xl relative overflow-hidden animate-fade-up">
        <!-- Header Section -->
        <div class="bg-[#002d5a] p-10 text-white text-center relative">
            <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]"></div>
            <div class="relative">
                <span class="text-[10px] font-black uppercase tracking-[0.4em] text-blue-300 mb-2 block">Formulario de Registro</span>
                <h1 class="font-serif text-4xl italic font-black mb-2">Super Carnes <span class="text-blue-400">Events</span></h1>
                <p class="text-xs font-bold text-slate-300 uppercase tracking-widest">{{ $event->nombre }}</p>
            </div>
        </div>

        <div class="p-10 sm:p-16">
            @if($errors->any())
                <div class="bg-red-50 border-2 border-red-100 text-red-700 p-6 rounded-3xl mb-10 animate-fade-up">
                    <p class="text-xs font-black uppercase tracking-widest mb-2 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        Errores de Validación
                    </p>
                    <ul class="text-sm font-medium opacity-90 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('participants.store', $event->slug) }}" class="space-y-8">
                @csrf
                <input type="hidden" id="tipo_evento" value="{{ $event->tipo_evento }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Personal Info -->
                    <div class="space-y-6 md:col-span-2">
                        <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 ml-2">Datos del Responsable</h3>
                        <div class="relative">
                            <input name="nombre_persona" value="{{ old('nombre_persona') }}" required class="w-full bg-slate-50 border-2 border-transparent rounded-2xl p-5 font-bold text-slate-700 focus:bg-white focus:border-blue-500 outline-none transition-all shadow-sm" placeholder="Nombre Completo">
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <input name="cedula" value="{{ old('cedula') }}" required class="w-full bg-slate-50 border-2 border-transparent rounded-2xl p-5 font-bold text-slate-700 focus:bg-white focus:border-blue-500 outline-none transition-all shadow-sm" placeholder="Cédula / ID">
                            <input name="telefono" value="{{ old('telefono') }}" required class="w-full bg-slate-50 border-2 border-transparent rounded-2xl p-5 font-bold text-slate-700 focus:bg-white focus:border-blue-500 outline-none transition-all shadow-sm" placeholder="Celular (6XXXXXXX)">
                        </div>
                    </div>

                    <!-- Event Specific -->
                    <div class="space-y-6 md:col-span-2 pt-4 border-t border-slate-50">
                        <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 ml-2">Información de Participación</h3>
                        <input name="nombre_fonda" value="{{ old('nombre_fonda') }}" required class="w-full bg-slate-50 border-2 border-transparent rounded-2xl p-5 font-bold text-slate-700 focus:bg-white focus:border-blue-500 outline-none transition-all shadow-sm" placeholder="Nombre del Equipo / Fonda">
                        <input name="plato_preparar" value="{{ old('plato_preparar') }}" required class="w-full bg-slate-50 border-2 border-transparent rounded-2xl p-5 font-bold text-slate-700 focus:bg-white focus:border-blue-500 outline-none transition-all shadow-sm" placeholder="Propuesta Gastronómica o Plato Estrella">
                        <textarea name="ubicacion" required rows="2" class="w-full bg-slate-50 border-2 border-transparent rounded-2xl p-5 font-bold text-slate-700 focus:bg-white focus:border-blue-500 outline-none transition-all shadow-sm" placeholder="Ubicación Exacta / Punto de Referencia">{{ old('ubicacion') }}</textarea>
                    </div>

                    <!-- Extra Dynamic Fields -->
                    <div id="rock_fields" class="hidden md:col-span-2">
                        <input name="nombre_banda" value="{{ old('nombre_banda') }}" class="w-full bg-blue-50 border-2 border-blue-100 rounded-2xl p-5 font-bold text-blue-900 focus:bg-white focus:border-blue-500 outline-none transition-all shadow-sm" placeholder="Nombre de la Banda">
                    </div>

                    <div id="bbq_fields" class="hidden md:col-span-2">
                        <input name="tipo_corte" value="{{ old('tipo_corte') }}" class="w-full bg-orange-50 border-2 border-orange-100 rounded-2xl p-5 font-bold text-orange-900 focus:bg-white focus:border-orange-500 outline-none transition-all shadow-sm" placeholder="Tipo de Corte (Ej: Brisket, Ribs)">
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit" class="w-full bg-[#002d5a] hover:bg-blue-900 text-white font-black py-6 rounded-[2rem] shadow-2xl shadow-blue-900/40 uppercase tracking-[0.3em] text-sm transform hover:scale-[1.02] active:scale-95 transition-all duration-300">
                        Confirmar Inscripción
                    </button>
                    <p class="text-center text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-6">
                        Al registrarte aceptas las bases y condiciones del evento.
                    </p>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const tipo = document.getElementById('tipo_evento').value;
            const rock = document.getElementById('rock_fields');
            const bbq = document.getElementById('bbq_fields');

            if (tipo === 'rock_fest') {
                rock.classList.remove('hidden');
                rock.querySelector('input').required = true;
            }

            if (tipo === 'bbq_challenge') {
                bbq.classList.remove('hidden');
                bbq.querySelector('input').required = true;
            }
        });
    </script>
</body>
</html>
