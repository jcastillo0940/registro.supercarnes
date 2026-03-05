# Auditoría completa de funciones del sistema

## Alcance
Se auditó la capa de controladores, modelos, servicio CSV y definición de rutas del sistema Laravel.

Archivos revisados:
- `app/Http/Controllers/AdminController.php`
- `app/Http/Controllers/Auth/LoginController.php`
- `app/Http/Controllers/CriterioController.php`
- `app/Http/Controllers/FondaController.php`
- `app/Http/Controllers/FondaController(1).php`
- `app/Http/Controllers/VotacionJuradoController.php`
- `app/Models/Criterio.php`
- `app/Models/Evaluacion.php`
- `app/Models/Fonda.php`
- `app/Models/User.php`
- `app/Services/CsvService.php`
- `routes/web.php`

---

## 1) Inventario funcional

### AdminController
| Función | Objetivo | Observaciones |
|---|---|---|
| `index()` | Lista fondas con evaluaciones y ordena por puntaje final | Calcula ranking en memoria (`get()->sortByDesc`) |
| `participantes()` | Lista todas las fondas | Sin paginación |
| `usuarios()` | Lista usuarios | Sin paginación |
| `logistica()` | Lista participantes por orden de visita | Correcta para secuencia logística |
| `guardarOrden(Request)` | Actualiza `orden_visita` en lote | Valida que `orden` sea array, no valida tipo/rango de posiciones |
| `crearUsuario(Request)` | Crea usuarios desde admin | No valida request; riesgo de datos incompletos y rol inválido |
| `eliminarFonda(Fonda)` | Elimina fonda | No contempla cascada manual de datos relacionados |
| `ajustarPuntos(Request, Fonda)` | Ajuste manual de puntaje | Sin validación de rango/tipo de ajuste |
| `exportar()` | Exporta resultados CSV | Usa servicio que emite headers directamente |
| `descargarPDF()` | Descarga PDF de logística | Dependencia de vista PDF |

### LoginController
| Función | Objetivo | Observaciones |
|---|---|---|
| `login(Request)` | Autenticación y redirección por rol | Correcto flujo base; depende de columna `role` |
| `logout(Request)` | Cierre de sesión seguro | Invalida sesión y token correctamente |

### CriterioController
| Función | Objetivo | Observaciones |
|---|---|---|
| `index()` | Listar criterios | Correcto |
| `store(Request)` | Crear criterio | Uso de `$request->all()` sin validación |
| `destroy(Criterio)` | Eliminar criterio | Correcto en estructura, sin controles adicionales |

### FondaController (activo)
| Función | Objetivo | Observaciones |
|---|---|---|
| `register()` | Render formulario de inscripción | Correcto |
| `store(Request)` | Registra fonda y genera QR | Usa transacción y control de errores; maneja filesystem en `public_html` |
| `panelJurado()` | Panel jurado con estado de votos del usuario actual | Carga relación filtrada correctamente |
| `scannerQR()` | Vista escáner QR | Correcto |
| `evaluar(Fonda)` | Formulario de evaluación con bloqueo de doble voto | Prevención inicial de doble voto correcta |
| `guardarEvaluacion(Request, Fonda)` | Persistencia de evaluación por criterios | Valida entrada y criterios activos; riesgo de carrera por doble envío concurrente |

### FondaController(1).php (duplicado)
- Contiene una segunda clase `FondaController` con la misma responsabilidad, implementación similar y diferencias de generación de QR.
- Riesgo alto de mantenimiento/confusión en despliegues o autoload según configuración.

### VotacionJuradoController
| Función | Objetivo | Observaciones |
|---|---|---|
| `index(Request)` | Ver jurados y votos agrupados por fonda | Correcto para consulta por jurado |
| `consolidado()` | Vista consolidada general | Calcula total criterios pero no usa optimizaciones |
| `descargarConsolidadoPDF()` | PDF consolidado con ranking por suma de puntajes | Ordenamiento en memoria; aceptable para volúmenes bajos |

### Modelos

#### `Fonda`
- `evaluaciones()`: relación hasMany.
- `getPromedioAttribute()`: promedio redondeado de `puntaje`.
- `getPuntajeFinalAttribute()`: promedio + ajuste admin.

#### `Evaluacion`
- Relaciones: `jurado()`, `fonda()`, `criterio()`.

#### `User`
- `evaluaciones()`: relación hasMany.
- **Observación**: `role` no está en `$fillable`; puede afectar creación en `AdminController::crearUsuario`.

#### `Criterio`
- Sin métodos de dominio adicionales.

### Servicio CSV
#### `CsvService::generate($data)`
- Emite headers y escribe CSV a `php://output`.
- No retorna objeto `Response`; funciona, pero reduce testabilidad y composición HTTP.

### Rutas (`web.php`)
- Rutas públicas de registro.
- Rutas autenticadas para jurado y admin.
- **Observación**: no se aplica middleware por rol en rutas admin/jurado, solo `auth`.

---

## 2) Hallazgos críticos y riesgos

## Alta prioridad
1. **Falta de autorización por rol en rutas administrativas.**
   - Cualquier usuario autenticado podría acceder a endpoints `/admin/*` si conoce la URL.

2. **Creación de usuario sin validación y posible falla silenciosa de rol.**
   - `crearUsuario` no valida campos.
   - `User::$fillable` no incluye `role`, por lo que ese valor puede no persistirse.

3. **Archivo duplicado de controlador (`FondaController(1).php`).**
   - Duplicidad de clase con mismo nombre: riesgo de errores de carga y deuda técnica.

## Media prioridad
4. **Falta de validación de negocio en ajustes y orden de logística.**
   - `ajustarPuntos` y `guardarOrden` deberían validar tipos/rangos.

5. **Posible condición de carrera en votación.**
   - Existe doble validación de “ya votó”, pero sin restricción única DB (`user_id`,`fonda_id`,`criterio_id` o regla equivalente).

6. **Uso de `$request->all()` en creación de criterios.**
   - Incrementa superficie de mass assignment.

## Baja prioridad
7. **Consultas y ordenamientos en memoria (`get()->sortByDesc`).**
   - Escalará mal con alto volumen.

8. **Servicio CSV sin `Response` explícito.**
   - Menor interoperabilidad con middleware/caché/test.

---

## 3) Recomendaciones puntuales

1. **Autorización robusta**
   - Implementar middleware de rol (`role:admin`, `role:jurado`) o Policies/Gates.

2. **Validación en controladores admin**
   - `crearUsuario`: validar nombre, email único, password robusta, rol permitido.
   - `ajustarPuntos`: validar numérico/rango.
   - `guardarOrden`: validar enteros positivos.

3. **Alinear modelo User**
   - Incluir `role` en `$fillable` o usar asignación explícita segura.

4. **Eliminar duplicados**
   - Remover `FondaController(1).php` y dejar una sola implementación oficial.

5. **Integridad en BD para evaluaciones**
   - Agregar índice único de negocio para prevenir doble votación por concurrencia.

6. **Refactor de exportación CSV**
   - Retornar `response()->streamDownload(...)` para mejor manejo HTTP.

7. **Mejoras de rendimiento**
   - Llevar ordenamientos al query builder cuando sea posible.

---

## 4) Conclusión ejecutiva
El sistema tiene una base funcional clara y buenas prácticas parciales (transacciones, validaciones en registro/evaluación, cierre de sesión seguro). Sin embargo, la exposición de rutas administrativas sin control de rol, la creación de usuarios sin validación robusta y la duplicidad de controlador representan riesgos importantes de seguridad y mantenibilidad. Se recomienda priorizar esos tres puntos antes de siguiente despliegue.
