<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FondaController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CriterioController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\VotacionJuradoController;

/*
|--------------------------------------------------------------------------
| Rutas Públicas
|--------------------------------------------------------------------------
*/
Route::get('/', [FondaController::class, 'register'])->name('fonda.register');
Route::post('/register-fonda', [FondaController::class, 'store'])->name('fonda.store');

/*
|--------------------------------------------------------------------------
| Autenticación
|--------------------------------------------------------------------------
*/
Route::get('/login', function () { return view('auth.login'); })->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Rutas Protegidas (Requieren Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // --- Módulo del Jurado ---
    Route::get('/panel-jurado', [FondaController::class, 'panelJurado'])->name('jurado.panel');
    Route::get('/scanner-qr', [FondaController::class, 'scannerQR'])->name('jurado.scanner');
    Route::get('/evaluar/{fonda}', [FondaController::class, 'evaluar'])->name('jurado.evaluar');
    Route::post('/evaluar/{fonda}', [FondaController::class, 'guardarEvaluacion']);
    Route::get('/consolidado-pdf', [VotacionJuradoController::class, 'descargarConsolidadoPDF'])->name('admin.consolidado.pdf');
    
    // --- Módulo Administrativo ---
    Route::prefix('admin')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::post('/ajustar/{fonda}', [AdminController::class, 'ajustarPuntos']);
        Route::get('/votaciones-por-jurado', [VotacionJuradoController::class, 'index'])->name('admin.votaciones.index');
        Route::get('/consolidado-general', [VotacionJuradoController::class, 'consolidado'])->name('admin.consolidado');
        
        Route::get('/criterios', [CriterioController::class, 'index'])->name('admin.criterios');
        Route::post('/criterios', [CriterioController::class, 'store']);
        Route::delete('/criterios/delete/{criterio}', [CriterioController::class, 'destroy']);
        
        Route::get('/participantes', [AdminController::class, 'participantes'])->name('admin.participantes');
        Route::delete('/participantes/{fonda}', [AdminController::class, 'eliminarFonda']);
        
        Route::get('/usuarios', [AdminController::class, 'usuarios'])->name('admin.usuarios');
        Route::post('/usuarios', [AdminController::class, 'crearUsuario']);
        
        Route::get('/logistica', [AdminController::class, 'logistica'])->name('admin.logistica');
        Route::post('/guardar-orden', [AdminController::class, 'guardarOrden'])->name('admin.guardarOrden');
        Route::get('/descargar-pdf', [AdminController::class, 'descargarPDF'])->name('admin.pdf');
    });
});