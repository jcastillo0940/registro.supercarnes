<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <title>Login - Fonda Challenge</title>
    <style>
        body { font-family: 'Inter', sans-serif; }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fadeIn {
            animation: fadeIn 0.6s ease-out;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-[#004691] to-[#002d5a] min-h-screen flex items-center justify-center p-4">
    
    <div class="w-full max-w-md animate-fadeIn">
        
        <!-- Logo y Título -->
        <div class="text-center mb-8">
            <div class="mb-6">
                <img src="https://apps.supercarnes.com/wp-content/uploads/elementor/thumbs/Recurso-1-qmfaz40h3cog36mfzpzjw2skienqu9j8xprd9b7uha.png" 
                     alt="Super Carnes" 
                     class="w-32 sm:w-40 md:w-48 mx-auto">
            </div>
            <h1 class="text-white text-3xl sm:text-4xl md:text-5xl font-black uppercase italic tracking-tight mb-2">
                Fonda Challenge
            </h1>
            <p class="text-[#FFD100] text-xs sm:text-sm font-bold uppercase tracking-widest">
                Del Productor al Consumidor
            </p>
        </div>

        <!-- Card de Login -->
        <div class="bg-white rounded-3xl sm:rounded-[2.5rem] p-6 sm:p-8 md:p-10 shadow-2xl">
            
            <!-- Header -->
            <div class="text-center mb-6 sm:mb-8">
                <h2 class="text-xl sm:text-2xl md:text-3xl font-black text-[#004691] uppercase tracking-tight mb-2">
                    Acceso Privado
                </h2>
                <p class="text-gray-500 text-xs sm:text-sm font-semibold">
                    Jurados y Administradores
                </p>
            </div>

            <!-- Mensajes de Error -->
            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-xl mb-6">
                    <div class="flex items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div class="text-xs sm:text-sm">
                            <p class="font-bold mb-1">Error al iniciar sesión:</p>
                            <ul class="space-y-0.5">
                                @foreach($errors->all() as $error)
                                    <li>• {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Formulario -->
            <form action="{{ route('login') }}" method="POST" class="space-y-4 sm:space-y-5">
                @csrf
                
                <!-- Email -->
                <div>
                    <label class="block text-[9px] sm:text-[10px] font-black text-gray-400 uppercase tracking-wider mb-2 ml-1">
                        Correo Electrónico
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </div>
                        <input type="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               placeholder="tu@email.com" 
                               class="w-full pl-12 pr-4 py-3 sm:py-4 bg-gray-50 rounded-xl sm:rounded-2xl outline-none focus:ring-2 focus:ring-[#FFD100] transition-all text-sm sm:text-base font-semibold text-gray-700 border-2 border-transparent focus:border-[#FFD100]" 
                               required>
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-[9px] sm:text-[10px] font-black text-gray-400 uppercase tracking-wider mb-2 ml-1">
                        Contraseña
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input type="password" 
                               name="password" 
                               placeholder="••••••••" 
                               class="w-full pl-12 pr-4 py-3 sm:py-4 bg-gray-50 rounded-xl sm:rounded-2xl outline-none focus:ring-2 focus:ring-[#FFD100] transition-all text-sm sm:text-base font-semibold text-gray-700 border-2 border-transparent focus:border-[#FFD100]" 
                               required>
                    </div>
                </div>

                <!-- Botón de Submit -->
                <button type="submit" 
                        class="w-full bg-gradient-to-r from-[#FFD100] to-[#FFA500] hover:from-[#FFA500] hover:to-[#FFD100] text-[#004691] font-black py-4 sm:py-5 rounded-xl sm:rounded-2xl shadow-lg hover:shadow-xl transition-all duration-200 hover:scale-[1.02] active:scale-95 uppercase tracking-wider text-sm sm:text-base">
                    <span class="flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        Iniciar Sesión
                    </span>
                </button>

                <!-- Remember Me (Opcional) -->
                <div class="flex items-center justify-between pt-2">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" 
                               name="remember" 
                               class="w-4 h-4 text-[#FFD100] bg-gray-100 border-gray-300 rounded focus:ring-[#FFD100] focus:ring-2">
                        <span class="ml-2 text-xs sm:text-sm text-gray-600 font-semibold">Recordarme</span>
                    </label>
                </div>
            </form>

        </div>

        <!-- Link de Registro Público -->
        <div class="text-center mt-6 sm:mt-8">
            <p class="text-white/80 text-xs sm:text-sm mb-3">
                ¿Eres dueño de una fonda?
            </p>
            <a href="{{ route('fonda.register') }}" 
               class="inline-block bg-white/10 backdrop-blur-sm hover:bg-white/20 text-white font-bold py-3 px-6 sm:px-8 rounded-xl sm:rounded-2xl transition-all hover:scale-105 text-xs sm:text-sm uppercase tracking-wider border border-white/20">
                Registrar mi Fonda
            </a>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8 sm:mt-12">
            <p class="text-white/50 text-[10px] sm:text-xs uppercase tracking-widest">
                © 2026 Super Carnes
            </p>
        </div>

    </div>

</body>
</html>