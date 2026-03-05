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
    <label>Logo</label>
    <input type="file" name="logo" class="form-control" accept="image/*">
    @if(!empty($event?->logo_url))
        <img src="{{ $event->logo_url }}" class="mt-2" style="height:60px;width:60px;object-fit:cover;border-radius:8px;">
    @endif
</div>
<div class="mb-3">
    <label>Color primario</label>
    <input type="color" name="color_primario" class="form-control" value="{{ old('color_primario', $event->color_primario ?? '#1d4ed8') }}">
</div>
<div class="mb-3">
    <label>Color secundario</label>
    <input type="color" name="color_secundario" class="form-control" value="{{ old('color_secundario', $event->color_secundario ?? '#0f172a') }}">
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
