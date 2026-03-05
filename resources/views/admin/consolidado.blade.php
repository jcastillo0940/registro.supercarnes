@extends('layouts.admin')

@section('page_title', 'Consolidado General de Votaciones')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
        <div>
            <h3 class="text-lg font-bold text-slate-800">Matriz de Evaluación</h3>
            <p class="text-sm text-slate-500">Puntajes totales por jurado y fonda</p>
        </div>
        <a href="{{ route('admin.consolidado.pdf') }}" target="_blank" class="flex items-center px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-bold hover:bg-red-700 transition shadow-md">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
    </svg>
    Exportar PDF Oficial
</a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-800 text-white">
                    <th class="p-4 text-xs uppercase tracking-wider font-bold border-r border-slate-700 sticky left-0 bg-slate-800 z-10">Fondas / Participantes</th>
                    @foreach($jurados as $jurado)
                        <th class="p-4 text-xs uppercase tracking-wider font-bold text-center border-r border-slate-700">
                            {{ $jurado->name }}
                        </th>
                    @endforeach
                    <th class="p-4 text-xs uppercase tracking-wider font-bold text-center bg-blue-900">Total Final</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @foreach($fondas as $fonda)
                <tr class="hover:bg-blue-50/30 transition-colors">
                    <td class="p-4 font-bold text-slate-700 border-r border-slate-100 sticky left-0 bg-white shadow-[2px_0_5px_rgba(0,0,0,0.05)]">
                        {{ $fonda->nombre_fonda }}
                        <span class="block text-[10px] text-slate-400 font-normal uppercase">{{ $fonda->nombre_responsable }}</span>
                    </td>
                    
                    @php $sumaTotalFonda = 0; @endphp
                    
                    @foreach($jurados as $jurado)
                        @php
                            // Sumamos todos los puntajes que este jurado le dio a esta fonda
                            $puntos = $fonda->evaluaciones->where('user_id', $jurado->id)->sum('puntaje');
                            $sumaTotalFonda += $puntos;
                        @endphp
                        <td class="p-4 text-center border-r border-slate-100">
                            @if($puntos > 0)
                                <span class="inline-block px-3 py-1 bg-green-100 text-green-700 rounded-full font-bold text-sm">
                                    {{ $puntos }}
                                </span>
                            @else
                                <span class="text-slate-300 text-xs italic">Pendiente</span>
                            @endif
                        </td>
                    @endforeach

                    <td class="p-4 text-center bg-blue-50/50">
                        <span class="text-lg font-extrabold text-blue-700">
                            {{ $sumaTotalFonda }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<style>
    @media print {
        .no-print { display: none; }
        body { background: white; }
        .shadow-sm { shadow: none; border: 1px solid #ccc; }
    }
</style>
@endsection