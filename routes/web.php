<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ListaController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\Auth\GoogleController;
use Laravel\Socialite\Facades\Socialite;

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
    
    // Rutas de Listas
    Route::get('/listas/propias', [ListaController::class, 'propias'])->name('listas.propias');
    Route::get('/mis-listas', [ListaController::class, 'propias'])->name('mis-listas'); // Alias
    Route::get('/listas/compartidas', [ListaController::class, 'compartidas'])->name('listas.compartidas');
    Route::get('/listas-compartidas', [ListaController::class, 'compartidas'])->name('listas-compartidas'); // Alias
    Route::post('/listas/{lista}/share', [ListaController::class, 'share'])->name('listas.share');
    Route::post('/listas/{lista}/alternar-comprado', [ListaController::class, 'alternarComprado'])->name('listas.alternarComprado');
    Route::resource('listas', ListaController::class);
    
    // Rutas de Categorías
    Route::resource('categorias', CategoriaController::class);
    
    // Rutas de Productos
    Route::resource('productos', ProductoController::class);
});

Route::get('/auth/google/redirect', [GoogleController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');


require __DIR__.'/auth.php';