@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Eventos</h2>
    <a href="{{ route('admin.events.create') }}" class="btn btn-primary mb-3">Nuevo evento</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Slug</th>
                <th>Tipo votación</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($events as $event)
                <tr>
                    <td>{{ $event->id }}</td>
                    <td>{{ $event->nombre }}</td>
                    <td>{{ $event->slug }}</td>
                    <td>{{ $event->tipo_votacion }}</td>
                    <td>{{ $event->estado }}</td>
                    <td>
                        <a class="btn btn-sm btn-warning" href="{{ route('admin.events.edit', $event) }}">Editar</a>
                        <form action="{{ route('admin.events.destroy', $event) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar evento?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6">Sin eventos registrados.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $events->links() }}
</div>
@endsection
