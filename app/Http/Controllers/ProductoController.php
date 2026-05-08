<?php

namespace App\Http\Controllers;

use App\Models\Lista;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Lista $lista)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'categoria_id' => 'required|exists:categorias,id',
            'cantidad' => 'required|integer|min:1'
        ]);

        $producto = new Producto();
        $producto->name = $request->name;
        $producto->cantidad = $request->cantidad;
        $producto->categoria_id = $request->categoria_id;
        $producto->save();

        return back()->with('success', 'Producto añadido correctamente.');}

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
