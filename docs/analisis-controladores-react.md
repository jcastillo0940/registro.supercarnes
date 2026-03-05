# Análisis re-hecho del sistema: controladores y vistas React requeridas

## Decisión arquitectónica aplicada
- Se eliminó la dependencia de múltiples Blade views en `resources/views`.
- Se dejó una sola vista contenedora: `resources/views/app.blade.php`.
- Todas las pantallas ahora se resuelven por `reactPage(page, props)` y renderizan en `resources/js/spa.js`.

## Mapa controlador -> vista React

### Auth
- `LoginController@show` -> `auth.login`

### Público / Landing
- `LandingController@index` -> `landing`

### Registro y evaluación
- `FondaController@register` -> `fonda.register`
- `FondaController@store` (éxito) -> `fonda.success`
- `ParticipantController@create` -> `participants.register`
- `ParticipantController@store` (éxito) -> `fonda.success`
- `FondaController@panelJurado` -> `jurado.index`
- `FondaController@scannerQR` -> `jurado.scanner`
- `FondaController@evaluar` -> `fonda.evaluar`
- `EvaluationController@publicForm` -> `public.vote`

### Administración
- `AdminController@index` -> `admin.dashboard`
- `AdminController@participantes` -> `admin.participantes`
- `AdminController@usuarios` -> `admin.usuarios`
- `AdminController@logistica` -> `admin.logistica`
- `CriterioController@index` -> `admin.criterios`
- `VotacionJuradoController@index` -> `admin.votaciones_jurado`
- `VotacionJuradoController@consolidado` -> `admin.consolidado`
- `EventController@index` -> `admin.events.index`
- `EventController@create` -> `admin.events.create`
- `EventController@edit` -> `admin.events.edit`

### Resultados
- `ResultsController@publicIndex` y `@adminIndex` -> `results.index`

## Vista React central
- Archivo único de render: `resources/js/spa.js`.
- Contiene renderizadores por clave de página (`switch(page)`).

## Exportaciones (sin Blade)
- PDFs (`AdminController`, `VotacionJuradoController`, `ResultsController`) ahora usan `Pdf::loadHTML(...)`.
- Se evita depender de templates Blade para exportación.
