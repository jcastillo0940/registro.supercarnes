@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="bg-white p-6 rounded-2xl shadow-sm">
        <h2 class="text-2xl font-bold mb-4 text-[#004691]">Detalle de Votaciones por Jurado</h2>
        
        <form action="{{ route('admin.votaciones.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[250px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Seleccionar Jurado</label>
                <select name="jurado_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">-- Seleccione un jurado --</option>
                    @foreach($jurados as $jurado)
                        <option value="{{ $jurado->id }}" {{ request('jurado_id') == $jurado->id ? 'selected' : '' }}>
                            {{ $jurado->name }} ({{ $jurado->email }})
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-[#004691] text-white px-6 py-2 rounded-lg font-bold hover:bg-blue-800 transition">
                Ver Calificaciones
            </button>
        </form>
    </div>

    @if($juradoSeleccionado)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($votaciones as $fondaId => $evaluaciones)
                @php $fonda = $evaluaciones->first()->fonda; @endphp
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
                    <div class="bg-gray-50 p-4 border-b">
                        <h3 class="font-bold text-lg text-gray-800">{{ $fonda->nombre_fonda }}</h3>
                        <p class="text-sm text-gray-500">Responsable: {{ $fonda->nombre_persona }}</p>
                    </div>
                    
                    <div class="p-4">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="text-left text-gray-400 border-b">
                                    <th class="pb-2 font-medium">Criterio</th>
                                    <th class="pb-2 font-medium text-right">Puntaje</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($evaluaciones as $eval)
                                    <tr>
                                        <td class="py-3 text-gray-700">{{ $eval->criterio->nombre }}</td>
                                        <td class="py-3 text-right">
                                            <span class="bg-blue-100 text-blue-700 px-2.5 py-0.5 rounded-full font-bold">
                                                {{ $eval->puntaje }} / 10
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="border-t font-bold text-gray-800">
                                    <td class="pt-3">Promedio de esta fonda</td>
                                    <td class="pt-3 text-right text-blue-700">
                                        {{ round($evaluaciones->avg('puntaje'), 2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>

                        @if($evaluaciones->first()->notas)
                            <div class="mt-4 p-3 bg-yellow-50 rounded-lg border border-yellow-100">
                                <p class="text-xs font-bold text-yellow-800 uppercase">Comentarios del Jurado:</p>
                                <p class="text-sm text-yellow-900 italic">"{{ $evaluaciones->first()->notas }}"</p>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white p-12 text-center rounded-2xl shadow-sm">
                    <p class="text-gray-500 italic">Este jurado aún no ha realizado ninguna evaluación.</p>
                </div>
            @endforelse
        </div>
    @else
        <div class="bg-blue-50 border border-blue-100 p-8 text-center rounded-2xl">
            <p class="text-blue-700">Por favor, selecciona un jurado arriba para visualizar el desglose de sus votos.</p>
        </div>
    @endif
</div>
@endsection