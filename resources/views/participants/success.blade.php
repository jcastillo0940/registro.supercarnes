<<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <title>¡Registro Exitoso! - Fonda Challenge 2026</title>
    <style>
        body { font-family: 'Inter', sans-serif; }
        
        @keyframes fade-up {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes pulse-green {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .animate-fade-up { animation: fade-up 0.6s ease-out forwards; }
        .animate-pulse-green { animation: pulse-green 2s ease-in-out infinite; }
        
        .bg-supercarnes { background-color: #004691; }
        .text-supercarnes { color: #004691; }
        .bg-accent { background-color: #FFD100; }
    </style>
</head>
<body class="bg-supercarnes min-h-screen flex items-center justify-center p-0 sm:p-4">

    <div class="bg-white w-full max-w-xl min-h-screen sm:min-h-0 sm:rounded-[3.5rem] shadow-2xl overflow-hidden flex flex-col items-center justify-center p-8 sm:p-12 animate-fade-up">
        
        <!-- Ícono de Éxito Animado -->
        <div class="w-24 h-24 bg-green-100 text-green-600 rounded-full flex items-center justify-center mb-8 shadow-inner animate-pulse-green">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7" />
            </svg>
        </div>

        <!-- Título Principal -->
        <h2 class="text-3xl sm:text-4xl font-black text-supercarnes mb-3 uppercase italic leading-tight tracking-tighter text-center">
            ¡Inscripción Exitosa!
        </h2>
        
        <!-- Mensaje de Confirmación -->
        <p class="text-slate-500 font-medium mb-10 leading-relaxed text-center max-w-md">
            La fonda <span class="font-bold text-slate-800">"{{ $fonda->nombre_fonda }}"</span> ha sido registrada correctamente para el <span class="text-supercarnes font-bold">Fonda Challenge 2026</span>.
        </p>

        <!-- Tarjeta del QR -->
        <div class="w-full bg-slate-50 rounded-[2.5rem] p-6 mb-8 border-2 border-dashed border-slate-200 text-center">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-4">
                Tu Comprobante Digital
            </p>
            
            <!-- QR Code con Validación -->
            <div class="bg-white p-4 rounded-3xl shadow-sm inline-block mb-4 border border-slate-100">
                @if($fonda->qr_code)
                    <img src="{{ asset($fonda->qr_code) }}" 
                         alt="Código QR de {{ $fonda->nombre_fonda }}" 
                         class="w-44 h-44 mx-auto"
                         loading="lazy"
                         onerror="this.parentElement.innerHTML='<div class=\'w-44 h-44 flex items-center justify-center bg-red-50 text-red-500 text-xs font-bold rounded-2xl\'><div class=\'text-center p-4\'>QR no disponible</div></div>'">
                @else
                    <div class="w-44 h-44 flex items-center justify-center bg-red-50 text-red-500 text-xs font-bold rounded-2xl">
                        <div class="text-center p-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            QR no disponible
                        </div>
                    </div>
                @endif
            </div>

            <!-- ID de Registro -->
            <p class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                ID de Registro: <span class="text-supercarnes">#{{ str_pad($fonda->id, 3, '0', STR_PAD_LEFT) }}</span>
            </p>
        </div>

        <!-- Información del Registro -->
        <div class="w-full bg-blue-50 rounded-2xl p-5 mb-6 border border-blue-100">
            <div class="space-y-2 text-left">
                <div class="flex items-start">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-3 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <div>
                        <p class="text-[10px] font-bold text-blue-500 uppercase tracking-wider">Responsable</p>
                        <p class="text-sm font-bold text-blue-900">{{ $fonda->nombre_persona }}</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-3 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    <div>
                        <p class="text-[10px] font-bold text-blue-500 uppercase tracking-wider">Teléfono</p>
                        <p class="text-sm font-bold text-blue-900">{{ $fonda->telefono }}</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-3 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <div>
                        <p class="text-[10px] font-bold text-blue-500 uppercase tracking-wider">Ubicación</p>
                        <p class="text-sm font-bold text-blue-900">{{ $fonda->ubicacion }}</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-3 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    <div>
                        <p class="text-[10px] font-bold text-blue-500 uppercase tracking-wider">Plato Estrella</p>
                        <p class="text-sm font-bold text-blue-900">{{ $fonda->plato_preparar }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Instrucciones Importantes -->
        <div class="w-full bg-yellow-50 rounded-2xl p-5 mb-8 border-2 border-yellow-200">
            <div class="flex items-start">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-left">
                    <p class="text-sm font-black text-yellow-800 mb-2 uppercase tracking-wide">
                        📱 Instrucciones Importantes
                    </p>
                    <ul class="text-xs text-yellow-700 space-y-1 leading-relaxed">
                        <li>✓ <strong>Descarga</strong> tu código QR ahora</li>
                        <li>✓ <strong>Imprímelo</strong> o tenlo en tu celular</li>
                        <li>✓ <strong>Preséntalo</strong> el día del evento</li>
                        <li>✓ Los jueces lo <strong>escanearán</strong> para calificarte</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Botones de Acción -->
        <div class="w-full space-y-4">
            <!-- Botón de Descarga del QR -->
            @if($fonda->qr_code)
                <a href="{{ asset($fonda->qr_code) }}" 
                   download="QR_{{ str_replace(' ', '_', $fonda->nombre_fonda) }}_{{ $fonda->id }}.png" 
                   class="flex items-center justify-center w-full bg-accent hover:bg-[#e6bd00] text-supercarnes font-black py-5 rounded-2xl transition-all transform active:scale-95 uppercase text-xs tracking-[0.15em] shadow-xl shadow-yellow-500/20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Descargar Código QR
                </a>
            @else
                <div class="w-full bg-red-100 text-red-700 font-bold py-4 rounded-2xl text-center text-xs uppercase tracking-wider border-2 border-red-200">
                    ⚠️ QR no disponible para descarga
                </div>
            @endif

            <!-- RUTA CORREGIDA: Usando route('fonda.register') que SÍ existe -->
            <a href="{{ route('fonda.register') }}" 
               class="flex items-center justify-center w-full bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold py-4 rounded-2xl transition-all uppercase text-[10px] tracking-[0.2em]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Registrar Otra Fonda
            </a>

            <!-- Enlace de Ayuda -->
            <div class="text-center pt-2">
                <p class="text-xs text-slate-400">
                    ¿Problemas con tu registro? 
                    <a href="/cdn-cgi/l/email-protection#fc8f938c938e8899bc8f898c998e9f9d8e92998fd29f9391" class="text-supercarnes font-bold hover:underline">
                        Contáctanos
                    </a>
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-12 text-center opacity-30">
            <p class="text-[8px] font-black text-supercarnes uppercase tracking-[0.4em]">
                Super Carnes 2026
            </p>
            <p class="text-[7px] text-slate-400 mt-1 uppercase tracking-widest">
                Del Productor al Consumidor
            </p>
        </div>
    </div>

</body>
</html></