<!DOCTYPE html>
<html lang="es">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
    <title>Sistema de Evaluación - Super Carnes</title>
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-900" x-data="{ sidebarOpen: false }">
    <div class="flex h-screen overflow-hidden">
        
        <div 
            x-show="sidebarOpen" 
            @click="sidebarOpen = false"
            x-transition:enter="transition-opacity ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-20 bg-black bg-opacity-50 lg:hidden"
        ></div>

        <div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-30 w-72 bg-[#002d5a] transition duration-300 transform lg:translate-x-0 lg:static lg:inset-0 shadow-2xl">
            
            <button 
                @click="sidebarOpen = false" 
                class="absolute top-4 right-4 lg:hidden text-white hover:text-red-400 transition p-2 rounded-lg hover:bg-white/10">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            
            <div class="flex items-center justify-center py-8 border-b border-blue-900/50">
                <div class="text-center">
                    <span class="text-white text-xl font-extrabold tracking-tighter uppercase">Super Carnes</span>
                    <p class="text-blue-400 text-[10px] tracking-[0.2em] font-bold uppercase">Fonda Challenge</p>
                </div>
            </div>

            <nav class="mt-8 px-4 space-y-2 overflow-y-auto" style="max-height: calc(100vh - 200px);">
                <p class="text-blue-400/50 text-[10px] font-bold uppercase px-4 mb-2">Evaluación</p>
                <a class="flex items-center px-4 py-3 text-slate-300 hover:bg-blue-800 hover:text-white rounded-xl transition-all group" href="{{ route('jurado.panel') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-blue-400 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-sm font-semibold">Panel de Votación</span>
                </a>

                @if(Auth::user()->role === 'admin')
                <p class="text-blue-400/50 text-[10px] font-bold uppercase px-4 mt-8 mb-2">Administración</p>
                
                <a class="flex items-center px-4 py-3 text-slate-300 hover:bg-blue-800 hover:text-white rounded-xl transition-all group {{ request()->routeIs('admin.consolidado') ? 'bg-blue-800 text-white' : '' }}" href="{{ route('admin.consolidado') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-blue-400 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span class="text-sm font-semibold">Consolidado Final</span>
                </a>

                <a class="flex items-center px-4 py-3 text-slate-300 hover:bg-blue-800 hover:text-white rounded-xl transition-all group {{ request()->routeIs('admin.votaciones.index') ? 'bg-blue-800 text-white' : '' }}" href="{{ route('admin.votaciones.index') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-blue-400 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <span class="text-sm font-semibold">Votaciones por Jurado</span>
                </a>

                <a class="flex items-center px-4 py-3 text-slate-300 hover:bg-blue-800 hover:text-white rounded-xl transition-all group" href="{{ route('admin.dashboard') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-blue-400 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="text-sm font-semibold">Ranking General</span>
                </a>

                <a class="flex items-center px-4 py-3 text-slate-300 hover:bg-blue-800 hover:text-white rounded-xl transition-all group" href="{{ route('admin.participantes') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-blue-400 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span class="text-sm font-semibold">Participantes</span>
                </a>

                <a class="flex items-center px-4 py-3 text-slate-300 hover:bg-blue-800 hover:text-white rounded-xl transition-all group" href="{{ route('admin.logistica') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-blue-400 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.447-.894L15 7m0 10V7" />
                    </svg>
                    <span class="text-sm font-semibold">Ruta de Logística</span>
                </a>

                <a class="flex items-center px-4 py-3 text-slate-300 hover:bg-blue-800 hover:text-white rounded-xl transition-all group" href="{{ route('admin.usuarios') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-blue-400 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 01-12 0v1zm0 0h6v-1a6 6 0 0112 0v1z" />
                    </svg>
                    <span class="text-sm font-semibold">Gestión de Jueces</span>
                </a>
                @endif
            </nav>

            <div class="absolute bottom-0 w-full p-4">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="w-full flex items-center justify-center px-4 py-3 bg-red-500/10 text-red-400 hover:bg-red-500 hover:text-white rounded-xl transition-all font-bold text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Salir del Sistema
                    </button>
                </form>
            </div>
        </div>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="flex justify-between items-center p-6 bg-white shadow-sm">
                <div class="flex items-center">
                    <button @click="sidebarOpen = true" class="text-slate-500 lg:hidden mr-4">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M4 6h16M4 12h16M4 18h16" stroke-width="2" stroke-linecap="round"/></svg>
                    </button>
                    <h2 class="text-sm font-bold text-slate-400 uppercase tracking-widest">
                        @yield('page_title', 'Escritorio')
                    </h2>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-xs font-bold px-3 py-1 bg-blue-100 text-blue-700 rounded-full uppercase italic">
                        {{ Auth::user()->role }}
                    </span>
                    <div class="h-8 w-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-xs">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto p-8">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>