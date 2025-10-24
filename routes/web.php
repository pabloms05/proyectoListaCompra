<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ListaController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\Auth\GoogleController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Rutas que requieren autenticación
Route::middleware('auth')->group(function () {
    
    // Rutas del Dashboard/Home
    Route::get('/dashboard', function () {
        return redirect()->route('home');
    })->name('dashboard');
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    
    // Rutas del Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas específicas de Listas (Más específicas, deben ir antes del resource)
    Route::get('/listas/propias', [ListaController::class, 'propias'])->name('listas.propias');
    Route::get('/listas/compartidas', [ListaController::class, 'compartidas'])->name('listas.compartidas');
    Route::post('/listas/{lista}/share', [ListaController::class, 'share'])->name('listas.share');

    // Rutas Resource para Listas (listas.create, listas.store, listas.edit, listas.update, etc.)
    // Esta debe ir después de las rutas específicas de 'listas/*'
    Route::resource('listas', ListaController::class);
    
    // Rutas de categorías y productos anidadas dentro de listas
    // Estas rutas no están siendo usadas por la nueva lógica de edición/creación de lista completa.
    // Solo serían necesarias si editas categorías/productos individualmente en la vista 'show'.
    Route::prefix('listas/{lista}')->group(function () {
        // Categorías
        Route::post('categorias', [CategoriaController::class, 'store'])->name('categorias.store');
        Route::put('categorias/{categoria}', [CategoriaController::class, 'update'])->name('categorias.update');
        Route::delete('categorias/{categoria}', [CategoriaController::class, 'destroy'])->name('categorias.destroy');
        
        // Productos
        // Usamos post para la actualización del producto (simulando PUT/PATCH en un formulario que no soporta esos métodos)
        Route::post('productos', [ProductoController::class, 'store'])->name('productos.store');
        Route::post('productos/{producto}/update', [ProductoController::class, 'update'])->name('productos.update'); 
        Route::delete('productos/{producto}', [ProductoController::class, 'destroy'])->name('productos.destroy');
    });
});

// Rutas de autenticación con Google
Route::get('auth/google/redirect', [GoogleController::class, 'redirectToGoogle'])
    ->name('auth.google.redirect');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])
    ->name('auth.google.callback');

require __DIR__.'/auth.php';