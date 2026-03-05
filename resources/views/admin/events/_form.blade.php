@csrf
<div class="mb-3">
    <label>Nombre</label>
    <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $event->nombre ?? '') }}" required>
</div>
<div class="mb-3">
    <label>Slug</label>
    <input type="text" name="slug" class="form-control" value="{{ old('slug', $event->slug ?? '') }}" required>
</div>
<div class="mb-3">
    <label>Logo (ruta/URL)</label>
    <input type="text" name="logo" class="form-control" value="{{ old('logo', $event->logo ?? '') }}">
</div>
<div class="mb-3">
    <label>Color primario</label>
    <input type="text" name="color_primario" class="form-control" value="{{ old('color_primario', $event->color_primario ?? '') }}">
</div>
<div class="mb-3">
    <label>Fecha inicio</label>
    <input type="date" name="fecha_inicio" class="form-control" value="{{ old('fecha_inicio', isset($event->fecha_inicio) ? $event->fecha_inicio->format('Y-m-d') : '') }}">
</div>
<div class="mb-3">
    <label>Fecha fin</label>
    <input type="date" name="fecha_fin" class="form-control" value="{{ old('fecha_fin', isset($event->fecha_fin) ? $event->fecha_fin->format('Y-m-d') : '') }}">
</div>
<div class="mb-3">
    <label>Tipo de votación</label>
    <select name="tipo_votacion" class="form-control" required>
        @foreach(['publico' => 'Público', 'jurado' => 'Jurado', 'ambos' => 'Ambos'] as $value => $label)
            <option value="{{ $value }}" @selected(old('tipo_votacion', $event->tipo_votacion ?? 'jurado') === $value)>{{ $label }}</option>
        @endforeach
    </select>
</div>
<div class="mb-3">
    <label>Estado</label>
    <select name="estado" class="form-control" required>
        @foreach(['activo' => 'Activo', 'inactivo' => 'Inactivo', 'proximamente' => 'Próximamente'] as $value => $label)
            <option value="{{ $value }}" @selected(old('estado', $event->estado ?? 'proximamente') === $value)>{{ $label }}</option>
        @endforeach
    </select>
</div>
<button class="btn btn-success">Guardar</button>
