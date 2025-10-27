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
});

/* Rutas boton google */
Route::get('/auth/google/redirect', [GoogleController::class, 'redirect'])->name('auth.google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('auth.google.callback');


require __DIR__.'/auth.php';