<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
public function store(Request $request, Categoria $categoria)
{
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'cantidad' => 'required|integer|min:1',
        'imagen' => 'nullable|image|max:2048',
    ]);
    $data['categoria_id'] = $categoria->id;
    if ($request->hasFile('imagen')) {
        $path = $request->file('imagen')->store('productos', 'public');
        $data['imagen'] = $path;
    }
    $producto = \App\Models\Producto::create($data);
    return redirect()->route('listas.show', $categoria->lista); 
}

public function marcarComoCompletado(Producto $producto)
{
    $producto->update(['completed' => true]);
    // redirige a la lista correspondiente
    $lista = $producto->categoria->lista;
    return redirect()->route('listas.show', $lista);
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
