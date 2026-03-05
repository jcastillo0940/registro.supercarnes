<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,900;1,900&family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <title>Acceso Oficial | Super Carnes</title>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-serif { font-family: 'Playfair Display', serif; }
        @keyframes fade-in { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in { animation: fade-in 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>
</head>
<body class="bg-slate-950 min-h-screen flex items-center justify-center p-6 relative overflow-hidden">
    <!-- Animated Decor -->
    <div class="absolute inset-0 opacity-20 pointer-events-none">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-blue-600 rounded-full blur-[150px]"></div>
    </div>

    <div class="w-full max-w-md relative z-10 animate-fade-in">
        <!-- Logo & Brand -->
        <div class="text-center mb-10">
            <h1 class="font-serif text-5xl italic font-black text-white mb-2">Super Carnes</h1>
            <p class="text-[10px] font-black uppercase tracking-[0.5em] text-blue-400">Plataforma de Certificación</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-[3rem] p-10 sm:p-14 shadow-2xl relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-400 via-blue-600 to-blue-900"></div>
            
            <div class="mb-10 text-center">
                <h2 class="text-2xl font-black text-slate-900 uppercase italic tracking-tighter">Acceso Restringido</h2>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Solo Personal Autorizado</p>
            </div>

            @if($errors->any())
                <div class="bg-red-50 border-2 border-red-100 text-red-700 p-5 rounded-3xl mb-8">
                    <ul class="text-[10px] font-black uppercase tracking-widest space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="space-y-2">
                    <label class="block text-[8px] font-black text-slate-400 uppercase tracking-[0.3em] ml-4">Identidad Corporativa</label>
                    <input type="email" name="email" required value="{{ old('email') }}"
                           class="w-full bg-slate-50 border-2 border-transparent rounded-2xl p-5 font-bold text-slate-700 focus:bg-white focus:border-blue-600 outline-none transition-all placeholder:text-slate-300"
                           placeholder="tu@supercarnes.com">
                </div>

                <div class="space-y-2">
                    <label class="block text-[8px] font-black text-slate-400 uppercase tracking-[0.3em] ml-4">Llave de Acceso</label>
                    <input type="password" name="password" required
                           class="w-full bg-slate-50 border-2 border-transparent rounded-2xl p-5 font-bold text-slate-700 focus:bg-white focus:border-blue-600 outline-none transition-all placeholder:text-slate-300"
                           placeholder="••••••••">
                </div>

                <div class="pt-4">
                    <button type="submit" 
                            class="w-full bg-slate-900 hover:bg-blue-600 text-white font-black py-6 rounded-2xl shadow-xl shadow-blue-900/10 uppercase tracking-[0.3em] text-[10px] transform hover:scale-[1.02] active:scale-95 transition-all outline-none">
                        Entrar al Sistema
                    </button>
                </div>
            </form>
        </div>

        <!-- External Links -->
        <div class="mt-12 text-center space-y-6">
            <div class="space-y-2">
                <p class="text-slate-500 text-[10px] font-black uppercase tracking-widest">¿Eres un participante?</p>
                <a href="{{ route('landing') }}" 
                   class="inline-block text-blue-400 hover:text-white font-black uppercase tracking-[0.2em] text-[10px] transition-colors border-b-2 border-blue-400/20 pb-1">
                    Ver Eventos y Registrarse
                </a>
            </div>
            
            <p class="text-slate-700 text-[8px] font-black uppercase tracking-[0.5em]">Super Carnes S.A. 2026</p>
        </div>
    </div>
</body>
</html>