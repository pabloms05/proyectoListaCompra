<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lista;
use Illuminate\Support\Facades\Auth;
use App\Models\Categoria;

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

        // Cargar relaciones de productos con su categoría
        $lista->load('productos.categoria');

        // Indicar si el usuario es propietario
        $isOwner = $lista->owner_id === $user->id;

        // Agrupar productos por categoría
        $productosPorCategoria = $lista->productos->groupBy(function($producto) {
            return $producto->categoria ? $producto->categoria->name : 'Sin categoría';
        });

        return view('listas.show', compact('lista', 'isOwner', 'productosPorCategoria'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validación de los datos
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            // Validar que los productos sean un array
            'productos' => 'nullable|array',
            // Validar que cada producto_id exista y que la cantidad sea un entero > 0
            'productos.*.producto_id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
        ]);

        // 2. Crear la Lista principal
        $lista = Lista::create([
            'name' => $request->name,
            'description' => $request->description,
            'owner_id' => Auth::id(),
        ]);

        // 3. Vinculación de Productos (attach)
        if ($request->has('productos')) {
            $productsToAttach = [];
            foreach ($request->productos as $productData) {
                $productsToAttach[$productData['producto_id']] = [
                    'cantidad' => $productData['cantidad']
                    // Si necesitas guardar el categoria_id en la pivot:
                    // 'categoria_id' => Producto::find($productData['producto_id'])->categoria_id
                ];
            }
            // Adjuntar los productos a la lista, incluyendo la cantidad
            $lista->productos()->attach($productsToAttach);
        }

        // 4. Redirección
        return redirect()->route('listas.show', $lista)
            ->with('success', 'Lista creada exitosamente y productos vinculados.');
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
    public function edit(Lista $lista)
    {
        $user = Auth::user();
        if ($lista->owner_id !== $user->id) {
            abort(403, "No tienes permiso para editar esta lista.");
        }

        // 1. Obtener todas las categorías con sus productos para los SELECTS
        $categoriasMaestras = Categoria::with('productos')->get();

        // 2. Obtener los productos actualmente vinculados a esta lista, incluyendo la cantidad
        // Usamos withPivot('cantidad') para obtener el dato de la tabla intermedia
        $productosLista = $lista->productos()->withPivot('cantidad')->get();

        return view('listas.edit', compact('lista', 'categoriasMaestras', 'productosLista'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lista $lista)
    {
        $user = Auth::user();

        // 1. Autorización
        if ($lista->owner_id !== $user->id) {
            abort(403, "No tienes permiso para actualizar esta lista.");
        }

        // 2. Validación de los datos (Misma que en store)
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'productos' => 'nullable|array',
            'productos.*.producto_id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
        ]);

        // 3. Actualización de la lista principal
        $lista->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        // 4. Sincronización de Productos (sync)
        $syncData = [];
        if ($request->has('productos')) {
            foreach ($request->productos as $productData) {
                $productoId = $productData['producto_id'];
                $cantidad = $productData['cantidad'];

                // Preparamos el array de sincronización: [producto_id => [pivot_data]]
                $syncData[$productoId] = ['cantidad' => $cantidad];
            }
        }

        // Sincronizar la relación Muchos a Muchos. Esto añade, actualiza o elimina productos.
        $lista->productos()->sync($syncData);

        // 5. Redirección
        return redirect()->route('listas.show', $lista)
            ->with('success', 'Lista "' . $lista->name . '" actualizada y sincronizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lista $lista)
    {
        $user = Auth::user();

        // 1. Autorización: Asegurarse de que solo el propietario puede eliminar la lista.
        if ($lista->owner_id !== $user->id) {
            // Redirigir o abortar con un error 403
            abort(403, "No tienes permiso para eliminar esta lista. Solo el propietario puede hacerlo.");
        }

        // 2. Eliminación
        // Para asegurar una eliminación limpia (si no usas eliminación en cascada en la DB):

        // Opcional: Desvincular usuarios compartidos
        $lista->sharedUsers()->detach();

        // Finalmente, eliminar la lista principal
        $lista->delete();

        // 3. Redirección y mensaje de éxito
        return redirect()->route('listas.propias')
            ->with('success', 'La lista "' . $lista->name . '" ha sido eliminada exitosamente.');
    }
}
