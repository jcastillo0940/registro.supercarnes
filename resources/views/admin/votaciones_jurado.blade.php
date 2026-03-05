@extends('layouts.admin')

@section('page_title', 'Trazabilidad de Votos')

@section('content')
<div class="max-w-7xl mx-auto animate-fade-in space-y-10">
    
    <!-- Filter Header -->
    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100">
        <div class="flex flex-col lg:flex-row justify-between items-end gap-6">
            <div class="flex-1 space-y-4">
                <h2 class="text-[#002d5a] text-3xl font-black uppercase italic tracking-tighter">Auditoría de <span class="text-blue-600">Jurados</span></h2>
                <form action="{{ route('admin.votaciones.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
                    <div class="min-w-[300px] space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-4">Seleccionar Jurado</label>
                        <select name="jurado_id" class="w-full bg-slate-50 border-2 border-transparent rounded-2xl p-4 font-bold text-slate-700 focus:bg-white focus:border-blue-500 transition-all outline-none shadow-sm">
                            <option value="">-- Todos los jurados --</option>
                            @foreach($jurados as $jurado)
                                <option value="{{ $jurado->id }}" {{ request('jurado_id') == $jurado->id ? 'selected' : '' }}>
                                    {{ $jurado->name }} ({{ $jurado->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="bg-[#002d5a] text-white px-8 py-4 rounded-2xl font-black uppercase tracking-widest hover:bg-blue-900 transition-all transform hover:scale-105 shadow-xl shadow-blue-900/20">
                        Ver Calificaciones
                    </button>
                </form>
            </div>
        </div>
    </div>

    @if($juradoSeleccionado)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @forelse($votaciones as $participantId => $evaluaciones)
                @php $p = $evaluaciones->first()->participant; @endphp
                <div class="bg-white rounded-[3rem] shadow-xl border border-slate-100 overflow-hidden group hover:shadow-2xl transition-all duration-500">
                    <div class="bg-slate-50 p-8 border-b border-slate-100 relative overflow-hidden">
                        <div class="absolute -right-4 -top-4 text-6xl opacity-5">🏆</div>
                        <div class="relative">
                            <h3 class="font-black text-2xl text-[#002d5a] uppercase italic group-hover:text-blue-600 transition-colors">{{ $p->nombre_fonda }}</h3>
                            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">{{ $p->nombre_persona }}</p>
                        </div>
                    </div>
                    
                    <div class="p-8 space-y-6">
                        <table class="w-full">
                            <thead>
                                <tr class="text-[10px] font-black uppercase tracking-widest text-slate-300 border-b border-slate-50">
                                    <th class="pb-4 text-left">Criterio de Evaluación</th>
                                    <th class="pb-4 text-right">Puntaje</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($evaluaciones as $eval)
                                    <tr class="group/row">
                                        <td class="py-4 text-slate-600 font-bold italic">{{ $eval->criterio->nombre }}</td>
                                        <td class="py-4 text-right">
                                            <span class="inline-flex items-center justify-center w-10 h-10 bg-blue-50 text-blue-700 rounded-full font-black text-sm">
                                                {{ $eval->puntaje }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="border-t-2 border-[#002d5a]">
                                    <td class="pt-6 font-black uppercase text-xs tracking-widest text-[#002d5a]">Promedio Parcial</td>
                                    <td class="pt-6 text-right font-black text-2xl italic text-blue-600 font-serif">
                                        {{ number_format($evaluaciones->avg('puntaje'), 1) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>

                        @if($evaluaciones->first()->notas)
                            <div class="mt-8 p-6 bg-blue-50/50 rounded-2xl border border-blue-100 relative">
                                <span class="absolute -top-3 left-6 bg-blue-600 text-white text-[8px] font-black uppercase px-2 py-1 rounded">Notas del Jurado</span>
                                <p class="text-sm text-blue-900 italic leading-relaxed">"{{ $evaluaciones->first()->notas }}"</p>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white p-24 text-center rounded-[3rem] border-2 border-dashed border-slate-200">
                    <div class="text-6xl mb-4">⌛</div>
                    <p class="text-slate-400 font-medium italic">Este jurado aún no ha registrado evaluaciones oficiales.</p>
                </div>
            @endforelse
        </div>
    @else
        <div class="bg-blue-50/50 border-2 border-dashed border-blue-200 p-24 text-center rounded-[3rem] flex flex-col items-center">
            <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center text-blue-500 mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </div>
            <h3 class="text-[#002d5a] text-xl font-black uppercase italic tracking-tighter mb-2">Consulta de Transparencia</h3>
            <p class="text-slate-500 max-w-sm">Selecciona un jurado en el panel superior para visualizar el desglose detallado de sus votaciones.</p>
        </div>
    @endif
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