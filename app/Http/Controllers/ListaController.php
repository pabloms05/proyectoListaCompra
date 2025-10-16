<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Lista;
use App\Models\User;
use App\Models\Categoria;
use App\Models\Producto;

class ListaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $listasPropias = $user->listas()->get();
        $listasCompartidas = $user->listasCompartidas()->get();
        return view('home', compact('listasPropias', 'listasCompartidas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Lista $lista)
    {
        $user = Auth::user();

        // Verificar permisos: debe ser el owner o estar compartida con él
        $isOwner = $lista->owner_id === $user->id;
        $isShared = $lista->sharedUsers()->where('user_id', $user->id)->exists();
        if (!($isOwner || $isShared)) {
            abort(403, "No tienes permiso para ver esta lista.");
        }

        // Carga las categorías y productos con sus relaciones
        $lista->load('categorias.productos');

        return view('listas.show', compact('lista', 'isOwner'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
