@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Eventos</h2>
    <a href="{{ route('admin.events.create') }}" class="btn btn-primary mb-3">Nuevo evento</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Evento</th>
                <th>Tema</th>
                <th>Tipo evento</th><th>Tipo votación</th><th>Registro</th>
                <th>Votación Pública</th><th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($events as $event)
                <tr>
                    <td>{{ $event->id }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px;">
                            @if($event->logo_url)
                                <img src="{{ $event->logo_url }}" style="height:30px;width:30px;object-fit:cover;border-radius:6px;">
                            @endif
                            <div>
                                <strong>{{ $event->nombre }}</strong><br>
                                <small>{{ $event->slug }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span style="display:inline-block;width:14px;height:14px;background:{{ $event->color_primario ?: '#1d4ed8' }};border-radius:50%"></span>
                        <span style="display:inline-block;width:14px;height:14px;background:{{ $event->color_secundario ?: '#0f172a' }};border-radius:50%"></span>
                    </td>
                    <td>{{ $event->tipo_evento }}</td>
                    <td>{{ $event->tipo_votacion }}</td>
                    <td><a href="{{ route('participants.register', $event->slug) }}" target="_blank">/{{ $event->slug }}/registro</a></td>
                    <td>{{ $event->public_voting_enabled ? "Activa" : "Inactiva" }}</td>
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
                <tr><td colspan="9">Sin eventos registrados.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $events->links() }}
</div>
@endsection
