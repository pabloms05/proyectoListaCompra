<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lista;

class CategoriaController extends Controller
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
    public function create(Lista $lista)
    {
        // Verificar permisos (owner o compartido editable)
        return view('categorias.create', compact('lista'));
    }

    public function store(Request $request, Lista $lista)
    {
        // validar
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'imagen' => 'nullable|image|max:2048',
        ]);

        $data['lista_id'] = $lista->id;
        if ($request->hasFile('imagen')) {
            $path = $request->file('imagen')->store('categorias', 'public');
            $data['imagen'] = $path;
        }
        $categoria = \App\Models\Categoria::create($data);
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
