<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscripción - Fonda Challenge 2026</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .bg-supercarnes { background-color: #004691; }
        .text-supercarnes { color: #004691; }
        .bg-accent { background-color: #FFD100; }
        
        .error-shake {
            animation: shake 0.3s;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }

        /* Ajuste para pantalla completa sin scroll */
        html, body {
            height: 100%;
        }
        
        .form-container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .form-content {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
        }
        
        /* En móvil permitir scroll natural */
        @media (max-width: 1023px) {
            html, body {
                height: auto;
                overflow: auto;
            }
            
            .form-container {
                min-height: 100vh;
                height: auto;
            }
            
            .form-content {
                overflow: visible;
            }
        }
        
        /* Custom scrollbar */
        .form-content::-webkit-scrollbar {
            width: 6px;
        }
        
        .form-content::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        .form-content::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }
        
        .form-content::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Responsive adjustments */
        @media (min-width: 1024px) {
            .desktop-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 1rem;
            }
        }

        /* Carrusel infinito */
        @keyframes scroll {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(-50%);
            }
        }

        .animate-scroll {
            animation: scroll 20s linear infinite;
        }

        .carousel-container:hover .animate-scroll {
            animation-play-state: paused;
        }
    </style>
</head>
<body class="bg-supercarnes">

    <div class="form-container">
        <div class="bg-white min-h-screen lg:h-screen flex flex-col lg:flex-row">
            
            <!-- Columna Izquierda: Header y Logo -->
            <div class="lg:w-2/5 bg-supercarnes flex flex-col justify-center items-center py-6 px-4 lg:p-12">
                <div class="text-center">
                    <!-- Logo Super Carnes -->
                    <div class="mb-4 lg:mb-10">
                        <img src="https://apps.supercarnes.com/wp-content/uploads/elementor/thumbs/Recurso-1-qmfaz40h3cog36mfzpzjw2skienqu9j8xprd9b7uha.png" 
                             alt="Super Carnes" 
                             class="w-32 lg:w-72 mx-auto">
                    </div>
                    
                    <h1 class="text-2xl lg:text-6xl font-black text-white uppercase italic tracking-tighter leading-tight mb-1 lg:mb-4">
                        Fonda Challenge
                    </h1>
                    <p class="text-accent font-bold text-xs lg:text-base tracking-widest mb-6 lg:mb-8">
                        
                    </p>
                    
                    <div class="hidden lg:block bg-white/10 backdrop-blur-sm rounded-2xl p-6 mt-8">
                        <div class="flex items-start text-left">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-accent mr-3 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="text-xs text-white">
                                <p class="font-bold mb-1 text-accent">📋 Importante:</p>
                                <p>Al registrarte recibirás un <strong>código QR único</strong> que los jueces escanearán para calificarte durante el evento.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Formulario -->
            <div class="lg:w-3/5 flex flex-col justify-center form-content">
                <div class="p-6 lg:p-8">
                    
                    <!-- Título del Formulario -->
                    <div class="text-center mb-4 lg:mb-8">
                        <h2 class="text-xl lg:text-4xl font-black text-supercarnes uppercase italic tracking-tight mb-1 lg:mb-3">
                            Inscribe tu Fonda
                        </h2>
                        <p class="text-slate-500 text-xs lg:text-base font-semibold">
                            Completa el formulario y recibe tu código QR
                        </p>
                    </div>
                    
                    <!-- Mensajes -->
                    @if(session('success'))
                        <div class="bg-green-100 text-green-700 p-3 rounded-xl text-center font-bold mb-4 border border-green-300 text-sm">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="bg-red-100 text-red-700 p-3 rounded-xl mb-4 border border-red-300 error-shake text-xs">
                            <div class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div>
                                    <p class="font-bold mb-1">Corrige los errores:</p>
                                    <ul class="space-y-0.5">
                                        @foreach($errors->all() as $error)
                                            <li>• {{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('fonda.store') }}" method="POST" id="fondaForm">
                        @csrf

                        <!-- Responsable -->
                        <div class="mb-3 lg:mb-4">
                            <label class="block text-[9px] lg:text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1.5 ml-1">
                                Responsable <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="nombre_persona" 
                                   value="{{ old('nombre_persona') }}"
                                   required 
                                   maxlength="255"
                                   placeholder="Nombre y Apellido"
                                   class="w-full px-4 py-2.5 lg:py-3.5 bg-slate-50 border-2 @error('nombre_persona') border-red-300 @else border-slate-100 @enderror rounded-xl focus:border-accent outline-none transition-all font-semibold text-slate-700 text-sm lg:text-base">
                        </div>

                        <!-- Grid: Cédula y Teléfono -->
                        <div class="desktop-grid mb-3">
                            <div>
                                <label class="block text-[9px] font-black text-slate-400 uppercase tracking-wider mb-1.5 ml-1">
                                    Cédula <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="cedula" 
                                       value="{{ old('cedula') }}"
                                       required 
                                       placeholder="0-000-0000"
                                       maxlength="20"
                                       class="w-full px-4 py-2.5 bg-slate-50 border-2 @error('cedula') border-red-300 @else border-slate-100 @enderror rounded-xl focus:border-accent outline-none font-semibold text-slate-700 uppercase text-sm">
                            </div>

                            <div>
                                <label class="block text-[9px] font-black text-slate-400 uppercase tracking-wider mb-1.5 ml-1">
                                    Celular <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" 
                                       name="telefono" 
                                       value="{{ old('telefono') }}"
                                       required 
                                       placeholder="60000000" 
                                       maxlength="8"
                                       pattern="6[0-9]{7}"
                                       class="w-full px-4 py-2.5 bg-slate-50 border-2 @error('telefono') border-red-300 @else border-slate-100 @enderror rounded-xl focus:border-accent outline-none font-semibold text-slate-700 text-sm">
                                @error('telefono')
                                @else
                                    <p class="text-slate-400 text-[10px] mt-1 ml-1">8 dígitos, inicia con 6</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Nombre Fonda -->
                        <div class="mb-3">
                            <label class="block text-[9px] font-black text-slate-400 uppercase tracking-wider mb-1.5 ml-1">
                                Nombre de la Fonda <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="nombre_fonda" 
                                   value="{{ old('nombre_fonda') }}"
                                   required 
                                   maxlength="255"
                                   placeholder="Nombre comercial"
                                   class="w-full px-4 py-2.5 bg-slate-50 border-2 @error('nombre_fonda') border-red-300 @else border-slate-100 @enderror rounded-xl focus:border-accent outline-none font-semibold text-slate-700 text-sm">
                        </div>

                        <!-- Grid: Ubicación y Plato -->
                        <div class="desktop-grid mb-3">
                            <div>
                                <label class="block text-[9px] font-black text-slate-400 uppercase tracking-wider mb-1.5 ml-1">
                                    Ubicación <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="ubicacion" 
                                       value="{{ old('ubicacion') }}"
                                       required 
                                       maxlength="500"
                                       placeholder="Ej: Calle 3ra"
                                       class="w-full px-4 py-2.5 bg-slate-50 border-2 @error('ubicacion') border-red-300 @else border-slate-100 @enderror rounded-xl focus:border-accent outline-none font-semibold text-slate-700 text-sm">
                            </div>

                            <div>
                                <label class="block text-[9px] font-black text-slate-400 uppercase tracking-wider mb-1.5 ml-1">
                                    Plato Estrella <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="plato_preparar" 
                                       value="{{ old('plato_preparar') }}"
                                       required 
                                       maxlength="500"
                                       placeholder="Tu plato principal"
                                       class="w-full px-4 py-2.5 bg-slate-50 border-2 @error('plato_preparar') border-red-300 @else border-slate-100 @enderror rounded-xl focus:border-accent outline-none font-semibold text-slate-700 text-sm">
                            </div>
                        </div>

                        <!-- Info Mobile -->
                        <div class="lg:hidden bg-supercarnes/10 rounded-xl p-3 mb-3 border border-supercarnes/20 text-xs">
                            <p class="text-supercarnes"><strong>📋 Importante:</strong> Recibirás un código QR único para el evento.</p>
                        </div>

                        <!-- Botón -->
                        <button type="submit" 
                                id="submitBtn"
                                class="w-full bg-accent hover:scale-[1.02] active:scale-95 text-supercarnes font-black py-4 lg:py-5 rounded-2xl shadow-lg uppercase tracking-wider text-sm lg:text-base transition-all duration-200 disabled:opacity-50">
                            <span id="btnText">Inscribir Gratis</span>
                            <span id="btnLoading" class="hidden">
                                <svg class="animate-spin h-4 w-4 lg:h-5 lg:w-5 inline-block mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Procesando...
                            </span>
                        </button>

                        <p class="text-center text-[10px] text-slate-400 mt-2">
                            <span class="text-red-500">*</span> Campos obligatorios
                        </p>
                    </form>

                    <!-- Carrusel de Imágenes Decorativas -->
                    <div class="mt-6 overflow-hidden">
                        <div class="carousel-container">
                            <div class="carousel-track flex gap-4 animate-scroll">
                                <!-- Imágenes de comida y fondas (se repiten para efecto continuo) -->
                                <div class="carousel-item flex-shrink-0 w-24 h-24 rounded-xl overflow-hidden shadow-md">
                                    <img src="https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=200" alt="Comida 1" class="w-full h-full object-cover">
                                </div>
                                <div class="carousel-item flex-shrink-0 w-24 h-24 rounded-xl overflow-hidden shadow-md">
                                    <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=200" alt="Comida 2" class="w-full h-full object-cover">
                                </div>
                                <div class="carousel-item flex-shrink-0 w-24 h-24 rounded-xl overflow-hidden shadow-md">
                                    <img src="https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?w=200" alt="Comida 3" class="w-full h-full object-cover">
                                </div>
                                <div class="carousel-item flex-shrink-0 w-24 h-24 rounded-xl overflow-hidden shadow-md">
                                    <img src="https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=200" alt="Comida 4" class="w-full h-full object-cover">
                                </div>
                                <div class="carousel-item flex-shrink-0 w-24 h-24 rounded-xl overflow-hidden shadow-md">
                                    <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=200" alt="Comida 5" class="w-full h-full object-cover">
                                </div>
                                <div class="carousel-item flex-shrink-0 w-24 h-24 rounded-xl overflow-hidden shadow-md">
                                    <img src="https://images.unsplash.com/photo-1555939594-58d7cb561ad1?w=200" alt="Comida 6" class="w-full h-full object-cover">
                                </div>
                                <!-- Duplicados para efecto continuo -->
                                <div class="carousel-item flex-shrink-0 w-24 h-24 rounded-xl overflow-hidden shadow-md">
                                    <img src="https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=200" alt="Comida 1" class="w-full h-full object-cover">
                                </div>
                                <div class="carousel-item flex-shrink-0 w-24 h-24 rounded-xl overflow-hidden shadow-md">
                                    <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=200" alt="Comida 2" class="w-full h-full object-cover">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Footer -->
                <div class="bg-slate-50 p-3 text-center border-t border-slate-200">
                    <p class="text-[9px] text-slate-400 uppercase tracking-wider">
                        © 2026 Super Carnes
                    </p>
                </div>
            </div>

        </div>
    </div>

    <!-- JavaScript -->
    <script>
        const form = document.getElementById('fondaForm');
        const submitBtn = document.getElementById('submitBtn');
        const btnText = document.getElementById('btnText');
        const btnLoading = document.getElementById('btnLoading');

        form.addEventListener('submit', function(e) {
            submitBtn.disabled = true;
            btnText.classList.add('hidden');
            btnLoading.classList.remove('hidden');
        });

        const telefonoInput = document.querySelector('input[name="telefono"]');
        if (telefonoInput) {
            telefonoInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '');
                if (this.value.length > 8) {
                    this.value = this.value.slice(0, 8);
                }
                if (this.value.length >= 1 && this.value[0] !== '6') {
                    this.classList.add('border-red-300');
                } else {
                    this.classList.remove('border-red-300');
                }
            });
        }

        const cedulaInput = document.querySelector('input[name="cedula"]');
        if (cedulaInput) {
            cedulaInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9A-Za-z-]/g, '');
            });
        }
    </script>

</body>
</html>