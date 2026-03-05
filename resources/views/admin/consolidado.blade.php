@extends('layouts.admin')

@section('page_title', 'Consolidado Oficial de Votaciones')

@section('content')
<div class="max-w-7xl mx-auto animate-fade-in">
    <div class="flex justify-between items-end mb-10">
        <div>
            <h1 class="text-5xl font-black text-[#002d5a] uppercase italic tracking-tighter">Matriz de <span class="text-blue-600">Evaluación</span></h1>
            <p class="text-slate-500 font-medium tracking-wide">Puntajes consolidados por jurado y participante</p>
        </div>
        <a href="{{ route('admin.consolidado.pdf') }}" target="_blank" class="bg-red-600 hover:bg-red-700 text-white px-8 py-3 rounded-2xl font-black uppercase text-xs tracking-widest shadow-xl shadow-red-600/20 transition-all hover:scale-105 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Exportar PDF Oficial
        </a>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/50 overflow-hidden border border-slate-100">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-[#002d5a] text-white">
                        <th class="p-8 text-left text-[10px] font-black uppercase tracking-[0.2em] border-b border-blue-900 sticky left-0 bg-[#002d5a] z-10 shadow-[4px_0_10px_rgba(0,0,0,0.1)]">Participantes</th>
                        @foreach($jurados as $jurado)
                            <th class="p-8 text-center text-[10px] font-black uppercase tracking-[0.2em] border-b border-blue-900 min-w-[150px]">
                                <div class="space-y-1">
                                    <div class="text-blue-300">Jurado</div>
                                    <div class="text-sm font-serif italic">{{ $jurado->name }}</div>
                                </div>
                            </th>
                        @endforeach
                        <th class="p-8 text-center text-[10px] font-black uppercase tracking-[0.2em] border-b border-blue-900 bg-blue-600">Total Final</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($participants as $participant)
                    <tr class="group hover:bg-slate-50/80 transition-all duration-300">
                        <td class="p-8 font-bold text-[#002d5a] border-r border-slate-100 sticky left-0 bg-white group-hover:bg-slate-50 transition-colors z-10 shadow-[4px_0_10px_rgba(0,0,0,0.02)]">
                            <div class="text-lg uppercase italic font-black group-hover:text-blue-600 transition-colors">{{ $participant->nombre_fonda }}</div>
                            <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $participant->nombre_responsable }}</div>
                        </td>
                        
                        @php $sumaTotal = 0; @endphp
                        
                        @foreach($jurados as $jurado)
                            @php
                                $puntos = $participant->evaluaciones->where('user_id', $jurado->id)->sum('puntaje');
                                $sumaTotal += $puntos;
                            @endphp
                            <td class="p-8 text-center border-r border-slate-50">
                                @if($puntos > 0)
                                    <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-50 text-blue-700 rounded-full font-black italic text-lg shadow-sm border border-blue-100">
                                        {{ number_format($puntos, 0) }}
                                    </div>
                                @else
                                    <div class="text-slate-200 text-[10px] font-black uppercase tracking-widest italic opacity-50">Pendiente</div>
                                @endif
                            </td>
                        @endforeach

                        <td class="p-8 text-center bg-blue-50/30 group-hover:bg-blue-600/5 transition-colors">
                            <div class="text-3xl font-black text-blue-600 font-serif italic tracking-tighter">
                                {{ number_format($sumaTotal, 0) }}
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