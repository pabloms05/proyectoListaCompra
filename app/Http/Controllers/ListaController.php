<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lista;
use Illuminate\Support\Facades\Auth;

class ListaController extends Controller
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
        return view('listas.create');
    }

    /**
     * Muestra las listas propias del usuario autenticado.
     */
    public function propias()
    {
        $user = Auth::user();
        $listas = Lista::where('owner_id', $user->id)->get();
        return view('listas.propias', compact('listas'));
    }

    /**
     * Muestra las listas compartidas con el usuario autenticado.
     */
    public function compartidas()
    {
        $user = Auth::user();
        $listas = $user->sharedLists;
        return view('listas.compartidas', compact('listas'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Lista $lista)
    {
        $user = Auth::user();

        // Verificar permisos: que sea propietario o que la lista haya sido compartida con el usuario
        if (! $lista->userHasAccess($user->id)) {
            abort(403, "No tienes permiso para ver esta lista.");
        }

        // Cargar relaciones de categorías y productos
        $lista->load('categorias.productos');
        
        // Indicar si el usuario es propietario
        $isOwner = $lista->owner_id === $user->id;
        
        // Obtener las categorías con sus productos
        $categorias = $lista->categorias()->with('productos')->get();
        
        return view('listas.show', compact('lista', 'isOwner', 'categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $lista = new Lista();
        $lista->name = $request->name;
        $lista->description = $request->description;
        $lista->owner_id = Auth::id();
        $lista->save();

        return redirect()->route('listas.show', $lista)
            ->with('success', 'Lista creada exitosamente.');
    }

    /**
     * Share a list with another user.
     */
    public function share(Request $request, Lista $lista)
{
    $user = Auth::user();

    // Solo el propietario debe poder compartir
    if ($lista->owner_id !== $user->id) {
        abort(403, "Solo el propietario puede compartir esta lista.");
    }

    $data = $request->validate([
        'user_email' => 'required|email|exists:users,email'
    ]);

    $userToShare = \App\Models\User::where('email', $data['user_email'])->first();

    if ($userToShare) {
        // Evitar duplicados
        $lista->sharedUsers()->syncWithoutDetaching([
            $userToShare->id => ['role' => 'editor']
        ]);
    }

    return back()->with('status', 'Lista compartida correctamente.');
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
