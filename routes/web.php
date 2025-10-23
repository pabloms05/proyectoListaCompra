<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;


Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Rutas que requieren autenticación
Route::middleware('auth')->group(function () {
    // Redirigir /dashboard a /home
    Route::get('/dashboard', function () {
        return redirect()->route('home');
    })->name('dashboard');

    // Ruta principal después del login
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function() {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    
    // Rutas de listas
    Route::get('/listas/propias', [\App\Http\Controllers\ListaController::class, 'propias'])->name('listas.propias');
    Route::get('/listas/compartidas', [\App\Http\Controllers\ListaController::class, 'compartidas'])->name('listas.compartidas');
    Route::post('/listas/{lista}/share', [\App\Http\Controllers\ListaController::class, 'share'])->name('listas.share');
    Route::resource('listas', \App\Http\Controllers\ListaController::class);
    
    // Rutas de categorías y productos anidadas dentro de listas
    Route::prefix('listas/{lista}')->group(function () {
        // Categorías
        Route::post('categorias', [\App\Http\Controllers\CategoriaController::class, 'store'])->name('categorias.store');
        Route::put('categorias/{categoria}', [\App\Http\Controllers\CategoriaController::class, 'update'])->name('categorias.update');
        Route::delete('categorias/{categoria}', [\App\Http\Controllers\CategoriaController::class, 'destroy'])->name('categorias.destroy');
        
        // Productos
        Route::post('productos', [\App\Http\Controllers\ProductoController::class, 'store'])->name('productos.store');
        Route::post('productos/{producto}/update', [\App\Http\Controllers\ProductoController::class, 'update'])->name('productos.update');
        Route::delete('productos/{producto}', [\App\Http\Controllers\ProductoController::class, 'destroy'])->name('productos.destroy');
    });
});

// Rutas de autenticación con Google
Route::get('auth/google/redirect', [\App\Http\Controllers\Auth\GoogleController::class, 'redirectToGoogle'])
    ->name('auth.google.redirect');
Route::get('auth/google/callback', [\App\Http\Controllers\Auth\GoogleController::class, 'handleGoogleCallback'])
    ->name('auth.google.callback');

require __DIR__.'/auth.php';
