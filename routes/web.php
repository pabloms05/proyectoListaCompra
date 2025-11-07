<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ListaController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\Auth\GoogleController;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\CompartirListaController;


Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Rutas que requieren autenticación
Route::middleware('auth')->group(function () {
    
    // Rutas del Dashboard/Home
    Route::get('/dashboard', function () {
        return redirect()->route('welcome');
    })->name('dashboard');
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    
    // Rutas del Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Rutas de Listas - IMPORTANTE: Las rutas específicas ANTES del resource
    Route::get('/listas/propias', [ListaController::class, 'propias'])->name('listas.propias');
    Route::get('/mis-listas', [ListaController::class, 'propias'])->name('mis-listas'); // Alias
    
    // Ruta API para obtener listas compartidas (DEBE ir ANTES del resource)
    Route::get('/listas/compartidas', [CompartirListaController::class, 'compartidasConmigo'])
        ->name('listas.compartidas.api');
    
    // Ruta para la vista de listas compartidas
    Route::get('/listas-compartidas', function() {
        return view('listas.compartidas');
    })->name('listas-compartidas');
    
    Route::post('/listas/{lista}/share', [ListaController::class, 'share'])->name('listas.share');
    Route::post('/listas/{lista}/alternar-comprado', [ListaController::class, 'alternarComprado'])->name('listas.alternarComprado');
    
    // Resource DEBE ir AL FINAL (captura /listas/{id})
    Route::resource('listas', ListaController::class);
    
    // Rutas de Categorías
    Route::resource('categorias', CategoriaController::class);
    
    // Rutas de Productos
    Route::resource('productos', ProductoController::class);
});

/* Rutas boton google */
Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');

Route::middleware(['auth'])->group(function () {
    
    // Buscar usuarios
    Route::get('/usuarios/buscar', [CompartirListaController::class, 'buscarUsuarios'])
        ->name('usuarios.buscar');
    
    // Compartir lista
    Route::post('/listas/{id}/compartir', [CompartirListaController::class, 'compartir'])
        ->name('listas.compartir');
    
    // Obtener usuarios con acceso a una lista
    Route::get('/listas/{id}/usuarios-compartidos', [CompartirListaController::class, 'obtenerUsuariosCompartidos'])
        ->name('listas.usuarios-compartidos');
    
    // Revocar acceso
    Route::delete('/listas/{idLista}/compartir/{idUsuario}', [CompartirListaController::class, 'revocar'])
        ->name('listas.revocar');
    
    // Actualizar rol de usuario
    Route::patch('/listas/{idLista}/compartir/{idUsuario}', [CompartirListaController::class, 'actualizarRol'])
        ->name('listas.actualizar-rol');
});

require __DIR__.'/auth.php';