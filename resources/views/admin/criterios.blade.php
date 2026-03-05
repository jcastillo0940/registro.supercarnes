@extends('layouts.admin')
@section('content')
<div class="bg-white p-6 rounded-2xl shadow-sm">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Gestión de Criterios por Evento</h2>
        <button onclick="document.getElementById('modalAdd').classList.toggle('hidden')" class="bg-green-600 text-white px-4 py-2 rounded-lg">+ Nueva Pregunta</button>
    </div>

    <form class="mb-5" method="GET" action="{{ route('admin.criterios') }}">
        <label class="text-sm block mb-1">Filtrar por evento</label>
        <select name="event_id" class="border p-2 rounded" onchange="this.form.submit()">
            @foreach($events as $event)
                <option value="{{ $event->id }}" @selected((int)$eventId === (int)$event->id)>{{ $event->nombre }}</option>
            @endforeach
        </select>
    </form>

    <table class="w-full text-left">
        <thead><tr class="border-b"> <th class="p-3">Nombre del Criterio</th> <th class="p-3">Evento</th> <th class="p-3">Estado</th> <th class="p-3">Acciones</th> </tr></thead>
        <tbody>
            @forelse($criterios as $c)
            <tr class="border-b hover:bg-gray-50">
                <td class="p-3">{{ $c->nombre }}</td>
                <td class="p-3">{{ $c->event?->nombre }}</td>
                <td class="p-3"><span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs">{{ $c->activo ? 'Activo' : 'Inactivo' }}</span></td>
                <td class="p-3">
                    <form action="/admin/criterios/delete/{{$c->id}}" method="POST" class="inline">@csrf @method('DELETE') <button class="text-red-500 font-bold">Eliminar</button></form>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="p-3">No hay criterios para este evento.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div id="modalAdd" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
    <div class="bg-white p-6 rounded-xl w-full max-w-sm">
        <h3 class="text-xl font-bold mb-4">Añadir Criterio</h3>
        <form action="/admin/criterios" method="POST">
            @csrf
            <input type="text" name="nombre" class="w-full border p-2 rounded mb-4" placeholder="Ej: Sabor del plato" required>
            <select name="event_id" class="w-full border p-2 rounded mb-4" required>
                @foreach($events as $event)
                    <option value="{{ $event->id }}" @selected((int)$eventId === (int)$event->id)>{{ $event->nombre }}</option>
                @endforeach
            </select>
            <div class="flex items-center gap-2 mb-4">
                <input id="activo" type="checkbox" name="activo" value="1" checked>
                <label for="activo">Activo</label>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="this.closest('#modalAdd').classList.add('hidden')" class="bg-gray-200 px-4 py-2 rounded">Cancelar</button>
                <button class="bg-blue-600 text-white px-4 py-2 rounded">Guardar</button>
            </div>
        </form>
    </div>
</div>
@endsection
