<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,900;1,900&family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <title>Registro Exitoso - Super Carnes</title>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-serif { font-family: 'Playfair Display', serif; }
        @keyframes fade-in { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes pulse-soft { 0%, 100% { opacity: 1; } 50% { opacity: 0.7; } }
        .animate-fade-in { animation: fade-in 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>
</head>
<body class="bg-slate-900 min-h-screen flex items-center justify-center p-4 sm:p-10 relative overflow-hidden">
    <!-- Background Decor -->
    <div class="absolute inset-0 opacity-20 pointer-events-none">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-blue-600 rounded-full blur-[150px]"></div>
    </div>

    <div class="w-full max-w-xl bg-white rounded-[3.5rem] shadow-2xl relative overflow-hidden animate-fade-in">
        <!-- Success Header -->
        <div class="bg-[#002d5a] p-12 text-center text-white relative">
            <div class="mb-6 inline-flex items-center justify-center w-20 h-20 bg-blue-500/20 rounded-full border-4 border-blue-400/30">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <span class="text-[10px] font-black uppercase tracking-[0.4em] text-blue-300 mb-2 block">Confirmación Oficial</span>
            <h1 class="font-serif text-4xl italic font-black">¡Inscripción <span class="text-blue-400">Exitosa</span>!</h1>
        </div>

        <div class="p-10 sm:p-14 text-center">
            <p class="text-slate-500 font-bold mb-10 leading-relaxed italic">
                El participante <span class="text-[#002d5a] not-italic font-black">"{{ $fonda->nombre_fonda }}"</span> ha completado su registro oficial para este certamen.
            </p>

            <!-- QR Card -->
            <div class="bg-slate-50 rounded-[3rem] p-8 mb-10 border-2 border-dashed border-slate-200">
                <div class="bg-white p-5 rounded-[2rem] shadow-xl inline-block mb-6 border border-slate-100 transform hover:scale-105 transition-transform duration-500">
                    @if($fonda->qr_code)
                        <img src="{{ asset($fonda->qr_code) }}" 
                             alt="Código QR de Acceso" 
                             class="w-48 h-48 mx-auto grayscale-0 brightness-110">
                    @else
                        <div class="w-48 h-48 flex items-center justify-center bg-slate-50 text-slate-300 font-black text-xs">QR GENERATING...</div>
                    @endif
                </div>
                
                <div class="space-y-1">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em]">Token de Seguridad</p>
                    <p class="text-xs font-black text-[#002d5a] font-mono tracking-tighter">ID-{{ strtoupper(substr($fonda->uuid, 0, 8)) }}</p>
                </div>
            </div>

            <!-- Details List -->
            <div class="grid grid-cols-2 gap-4 mb-10">
                <div class="p-4 bg-slate-50 rounded-2xl text-left border border-slate-100">
                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block mb-1">Responsable</span>
                    <p class="text-[10px] font-black text-[#002d5a] uppercase">{{ $fonda->nombre_persona }}</p>
                </div>
                <div class="p-4 bg-slate-50 rounded-2xl text-left border border-slate-100">
                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block mb-1">Contacto</span>
                    <p class="text-[10px] font-black text-[#002d5a] uppercase">{{ $fonda->telefono }}</p>
                </div>
                <div class="p-4 bg-slate-50 rounded-2xl text-left border border-slate-100 col-span-2">
                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block mb-1">Locación Registrada</span>
                    <p class="text-[10px] font-black text-[#002d5a] uppercase truncate">{{ $fonda->ubicacion }}</p>
                </div>
            </div>

            <!-- Actions -->
            <div class="space-y-4">
                @if($fonda->qr_code)
                    <a href="{{ asset($fonda->qr_code) }}" download 
                       class="w-full bg-[#002d5a] hover:bg-blue-900 text-white font-black py-5 rounded-2xl shadow-xl shadow-blue-900/20 uppercase tracking-[0.2em] text-[10px] flex items-center justify-center transition-all hover:scale-[1.02] active:scale-95">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Descargar Pase Digital (QR)
                    </a>
                @endif
                
                <a href="{{ route('participants.register', $fonda->event->slug) }}" 
                   class="w-full bg-slate-100 hover:bg-slate-200 text-slate-500 font-black py-4 rounded-2xl uppercase tracking-widest text-[9px] flex items-center justify-center transition-all">
                    Nuevo Registro de Participante
                </a>
            </div>

            <!-- Notice -->
            <div class="mt-10 p-4 border-2 border-amber-100 bg-amber-50/50 rounded-2xl">
                <p class="text-[9px] text-amber-700 font-bold leading-relaxed px-4">
                    IMPORTANTE: Presenta este código QR al jurado calificador durante el recorrido oficial para activar tu evaluación inalámbrica.
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="p-8 bg-slate-50 text-center border-t border-slate-100 flex items-center justify-center space-x-6">
            <div class="text-[8px] font-black text-slate-300 uppercase tracking-[0.4em]">Super Carnes 2026</div>
        </div>
    </div>
</body>
</html>