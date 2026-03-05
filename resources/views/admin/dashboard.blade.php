@extends('layouts.admin')

@section('page_title', 'Ranking en Vivo')

@section('content')
<div class="max-w-7xl mx-auto">
    
    <!-- Header -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-8">
        <div>
            <h1 class="text-4xl lg:text-5xl font-black text-[#004691] uppercase italic tracking-tight">Ranking en Vivo</h1>
            <p class="text-slate-500 font-semibold mt-1">Actualización en tiempo real de los puntajes</p>
        </div>
        <a href="/admin/exportar" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-2xl font-black shadow-lg transition-all hover:scale-105 flex items-center space-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span>Descargar Excel</span>
        </a>
    </div>

    <!-- Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-6 border-2 border-blue-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-600 text-xs font-black uppercase tracking-wider mb-1">Total Fondas</p>
                    <p class="text-4xl font-black text-[#004691]">{{ count($fondas) }}</p>
                </div>
                <div class="bg-blue-200 rounded-2xl p-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-2xl p-6 border-2 border-yellow-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-700 text-xs font-black uppercase tracking-wider mb-1">Puntaje Más Alto</p>
                    <p class="text-4xl font-black text-yellow-700">{{ $fondas->first()->puntaje_final ?? 0 }}</p>
                </div>
                <div class="bg-yellow-200 rounded-2xl p-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-2xl p-6 border-2 border-green-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-700 text-xs font-black uppercase tracking-wider mb-1">Promedio General</p>
                    <p class="text-4xl font-black text-green-700">{{ number_format($fondas->avg('puntaje_final'), 1) }}</p>
                </div>
                <div class="bg-green-200 rounded-2xl p-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Ranking -->
    <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
        
        <!-- Desktop Table -->
        <div class="hidden lg:block overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-[#004691] to-[#0066cc] text-white">
                    <tr>
                        <th class="p-5 text-left uppercase text-xs font-black tracking-wider">Puesto</th>
                        <th class="p-5 text-left uppercase text-xs font-black tracking-wider">Fonda</th>
                        <th class="p-5 text-center uppercase text-xs font-black tracking-wider">Puntaje Jueces</th>
                        <th class="p-5 text-center uppercase text-xs font-black tracking-wider">Ajuste Manual</th>
                        <th class="p-5 text-center uppercase text-xs font-black tracking-wider">Puntaje Final</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($fondas as $fonda)
                    @php
                        $puesto = $loop->iteration; // Puesto real: 1, 2, 3, 4, etc.
                    @endphp
                    <tr class="{{ $puesto <= 3 ? 'bg-yellow-50/50' : 'hover:bg-gray-50' }} transition-colors">
                        
                        <!-- Puesto -->
                        <td class="p-5">
                            <div class="flex items-center space-x-3">
                                @if($puesto === 1)
                                    <span class="text-4xl">🥇</span>
                                @elseif($puesto === 2)
                                    <span class="text-4xl">🥈</span>
                                @elseif($puesto === 3)
                                    <span class="text-4xl">🥉</span>
                                @else
                                    <span class="text-2xl font-black text-gray-300">#{{ $puesto }}</span>
                                @endif
                            </div>
                        </td>

                        <!-- Fonda -->
                        <td class="p-5">
                            <div class="font-black text-[#004691] text-lg">{{ $fonda->nombre_fonda }}</div>
                            <div class="text-sm text-gray-500 font-medium">{{ $fonda->plato_preparar }}</div>
                        </td>

                        <!-- Puntaje Jueces -->
                        <td class="p-5 text-center">
                            <span class="inline-block bg-blue-100 text-blue-700 px-4 py-2 rounded-xl font-black text-lg">
                                {{ number_format($fonda->promedio, 1) }}
                            </span>
                        </td>

                        <!-- Ajuste Manual -->
                        <td class="p-5">
                            <form action="/admin/ajustar/{{ $fonda->id }}" method="POST" class="flex justify-center items-center space-x-2">
                                @csrf
                                <input type="number" 
                                       step="0.01" 
                                       name="ajuste" 
                                       value="{{ $fonda->ajuste_admin ?? 0 }}" 
                                       class="w-24 border-2 border-gray-200 rounded-xl text-center p-2 font-bold text-gray-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition">
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl font-bold transition-all hover:scale-105 shadow-md">
                                    ✓
                                </button>
                            </form>
                        </td>

                        <!-- Puntaje Final -->
                        <td class="p-5 text-center">
                            <span class="inline-block bg-gradient-to-r from-[#FFD100] to-[#FFA500] text-[#004691] px-6 py-3 rounded-2xl font-black text-2xl shadow-lg">
                                {{ number_format($fonda->puntaje_final, 1) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div class="lg:hidden divide-y divide-gray-100">
            @foreach($fondas as $fonda)
            @php
                $puesto = $loop->iteration; // Puesto real: 1, 2, 3, 4, etc.
            @endphp
            <div class="p-5 {{ $puesto <= 3 ? 'bg-yellow-50/50' : '' }}">
                
                <!-- Header Card -->
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        @if($puesto === 1)
                            <span class="text-3xl">🥇</span>
                        @elseif($puesto === 2)
                            <span class="text-3xl">🥈</span>
                        @elseif($puesto === 3)
                            <span class="text-3xl">🥉</span>
                        @else
                            <span class="text-xl font-black text-gray-300">#{{ $puesto }}</span>
                        @endif
                        <div>
                            <div class="font-black text-[#004691] text-lg">{{ $fonda->nombre_fonda }}</div>
                            <div class="text-xs text-gray-500 font-medium">{{ $fonda->plato_preparar }}</div>
                        </div>
                    </div>
                    <span class="bg-gradient-to-r from-[#FFD100] to-[#FFA500] text-[#004691] px-4 py-2 rounded-xl font-black text-xl shrink-0">
                        {{ number_format($fonda->puntaje_final, 1) }}
                    </span>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-2 gap-3 mb-3">
                    <div class="bg-blue-50 rounded-xl p-3 text-center border border-blue-100">
                        <div class="text-xs text-blue-600 font-bold uppercase mb-1">Puntaje Jueces</div>
                        <div class="text-xl font-black text-blue-700">{{ number_format($fonda->promedio, 1) }}</div>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-3 text-center border border-gray-100">
                        <div class="text-xs text-gray-600 font-bold uppercase mb-1">Ajuste</div>
                        <div class="text-xl font-black text-gray-700">{{ number_format($fonda->ajuste_admin ?? 0, 2) }}</div>
                    </div>
                </div>

                <!-- Form -->
                <form action="/admin/ajustar/{{ $fonda->id }}" method="POST" class="flex items-center space-x-2">
                    @csrf
                    <input type="number" 
                           step="0.01" 
                           name="ajuste" 
                           value="{{ $fonda->ajuste_admin ?? 0 }}" 
                           placeholder="Ajuste manual"
                           class="flex-1 border-2 border-gray-200 rounded-xl text-center p-3 font-bold text-gray-700 focus:border-blue-500 outline-none">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-blue-700 transition">
                        Aplicar
                    </button>
                </form>
            </div>
            @endforeach
        </div>

    </div>

</div>
@endsection