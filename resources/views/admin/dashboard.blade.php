@extends('layouts.admin')

@section('page_title', 'Ranking Real-Time')

@section('content')
<div class="max-w-7xl mx-auto animate-fade-in">
    
    <!-- Branding & Header -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 mb-10">
        <div class="space-y-1">
            <h1 class="text-5xl lg:text-6xl font-black text-[#002d5a] uppercase italic tracking-tighter leading-none">
                Ranking <span class="text-blue-600">Oficial</span>
            </h1>
            <p class="text-slate-500 font-medium tracking-wide flex items-center">
                <span class="relative flex h-3 w-3 mr-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                </span>
                Sincronizado en tiempo real con la base de datos
            </p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.pdf') }}" class="bg-white border-2 border-slate-200 text-slate-700 px-6 py-3 rounded-2xl font-bold shadow-sm hover:bg-slate-50 transition-all flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                <span>Lista Logística</span>
            </a>
            <a href="/admin/exportar" class="bg-[#002d5a] hover:bg-blue-900 text-white px-8 py-3 rounded-2xl font-black shadow-xl shadow-blue-900/20 transition-all hover:scale-105 flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                <span>Resultados CSV</span>
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        @php
            $totalParticipants = count($participants);
            $maxScore = $participants->first()?->final_score ?? 0;
            $avgScore = $totalParticipants > 0 ? $participants->avg('final_score') : 0;
        @endphp
        
        <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-50 rounded-full group-hover:scale-110 transition-transform"></div>
            <p class="text-slate-400 text-xs font-black uppercase tracking-widest mb-2 relative">Participantes</p>
            <div class="flex items-end justify-between relative">
                <p class="text-5xl font-black text-[#002d5a]">{{ $totalParticipants }}</p>
                <div class="p-3 bg-blue-600 rounded-2xl text-white shadow-lg shadow-blue-600/30">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-yellow-50 rounded-full group-hover:scale-110 transition-transform"></div>
            <p class="text-slate-400 text-xs font-black uppercase tracking-widest mb-2 relative">Puntaje Máximo</p>
            <div class="flex items-end justify-between relative">
                <p class="text-5xl font-black text-yellow-600 font-serif">{{ number_format($maxScore, 2) }}</p>
                <div class="p-3 bg-yellow-500 rounded-2xl text-white shadow-lg shadow-yellow-500/30">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-[#002d5a] rounded-[2rem] p-8 shadow-xl shadow-blue-900/20 relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-32 h-32 bg-white/5 rounded-full"></div>
            <p class="text-blue-300 text-xs font-black uppercase tracking-widest mb-2 relative">Promedio Global</p>
            <div class="flex items-end justify-between relative">
                <p class="text-5xl font-black text-white italic">{{ number_format($avgScore, 1) }}</p>
                <div class="p-3 bg-white/10 rounded-2xl text-white backdrop-blur-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Leaderboard Table -->
    <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/50 overflow-hidden border border-slate-100">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="p-8 text-left text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 border-b border-slate-100"># Rank</th>
                        <th class="p-8 text-left text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 border-b border-slate-100">Participante & Propuesta</th>
                        <th class="p-8 text-center text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 border-b border-slate-100">Score Jueces</th>
                        <th class="p-8 text-center text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 border-b border-slate-100">Bonus/Malus</th>
                        <th class="p-8 text-right text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 border-b border-slate-100">Total Final</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($participants as $participant)
                    @php
                        $rank = $loop->iteration;
                        $isTop3 = $rank <= 3;
                    @endphp
                    <tr class="group hover:bg-slate-50/80 transition-all duration-300">
                        <td class="p-8">
                            <div class="flex items-center">
                                @if($rank === 1)
                                    <div class="w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center text-white text-xl shadow-lg ring-4 ring-yellow-100">👑</div>
                                @elseif($rank === 2)
                                    <div class="w-10 h-10 bg-slate-300 rounded-full flex items-center justify-center text-white text-lg shadow-md ring-4 ring-slate-100">🥈</div>
                                @elseif($rank === 3)
                                    <div class="w-10 h-10 bg-orange-300 rounded-full flex items-center justify-center text-white text-lg shadow-md ring-4 ring-orange-100">🥉</div>
                                @else
                                    <span class="text-xl font-black text-slate-200 group-hover:text-slate-400 transition-colors tracking-tighter">#{{ str_pad($rank, 2, '0', STR_PAD_LEFT) }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="p-8">
                            <div class="space-y-1">
                                <div class="text-xl font-bold text-[#002d5a] group-hover:text-blue-600 transition-colors uppercase italic">{{ $participant->nombre_fonda }}</div>
                                <div class="text-sm font-medium text-slate-400 uppercase tracking-widest">{{ $participant->plato_preparar }}</div>
                            </div>
                        </td>
                        <td class="p-8 text-center">
                            <div class="inline-flex items-center justify-center px-4 py-2 bg-slate-100 rounded-xl font-black text-slate-600 group-hover:bg-blue-50 group-hover:text-blue-700 transition-all">
                                {{ number_format($participant->judge_avg ?? $participant->promedio, 2) }}
                            </div>
                        </td>
                        <td class="p-8">
                            <form action="/admin/ajustar/{{ $participant->id }}" method="POST" class="flex items-center justify-center space-x-2">
                                @csrf
                                <div class="relative group/input">
                                    <input type="number" step="0.01" name="ajuste" value="{{ $participant->ajuste_admin }}" 
                                        class="w-24 bg-slate-50 border-2 border-transparent rounded-xl p-2 text-center font-black text-slate-700 focus:bg-white focus:border-blue-500 transition-all outline-none">
                                    <div class="absolute inset-0 rounded-xl border-2 border-slate-100 pointer-events-none group-hover/input:border-slate-200"></div>
                                </div>
                                <button class="w-10 h-10 bg-white border-2 border-slate-100 text-blue-600 rounded-xl flex items-center justify-center hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-all shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </form>
                        </td>
                        <td class="p-8 text-right">
                            <div class="inline-block relative">
                                <div class="absolute inset-0 bg-blue-600 blur-xl opacity-0 group-hover:opacity-20 transition-opacity"></div>
                                <span class="relative text-3xl font-black italic tracking-tighter text-[#002d5a] group-hover:text-blue-700 transition-colors">
                                    {{ number_format($participant->final_score ?? $participant->puntaje_final, 2) }}
                                </span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>
@endsection