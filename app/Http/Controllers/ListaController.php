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
        $listas = Lista::where('owner_id', $user->id)
            ->withCount('productos')
            ->get();
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
        
        // Obtener el rol del usuario (si no es owner, buscar en la tabla pivot)
        $userRole = 'viewer'; // Por defecto
        if ($isOwner) {
            $userRole = 'owner';
        } else {
            $sharedUser = $lista->usuariosCompartidos()
                ->where('user_id', $user->id)
                ->first();
            if ($sharedUser) {
                $userRole = $sharedUser->pivot->role; // 'editor' o 'lector'
            }
        }
        
        // Determinar si es una lista compartida con el usuario (no es owner)
        $isSharedWithMe = !$isOwner;

        // Agrupar productos por categoría
        $productosPorCategoria = $lista->productos->groupBy(function ($producto) {
            return $producto->categoria ? $producto->categoria->nombre : 'Sin categoría';
        });

        return view('listas.show', compact('lista', 'isOwner', 'userRole', 'isSharedWithMe', 'productosPorCategoria'));
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
            'productos.*.producto_id' => 'required|exists:productos,id_producto',
            'productos.*.cantidad' => 'required|integer|min:1',
            // Validar productos nuevos
            'nuevos_productos' => 'nullable|array',
            'nuevos_productos.*.nombre' => 'required|string|max:255',
            'nuevos_productos.*.categoria_id' => 'required|exists:categorias,id_categoria',
            'nuevos_productos.*.cantidad' => 'required|integer|min:1',
        ]);

        // 2. Crear la Lista principal
        $lista = Lista::create([
            'name' => $request->name,
            'description' => $request->description,
            'owner_id' => Auth::id(),
            'compartida' => false,
        ]);

        // 3. Procesar nuevos productos primero
        $nuevosProductosCreados = [];
        if ($request->has('nuevos_productos')) {
            foreach ($request->nuevos_productos as $nuevoProducto) {
                // Crear el producto en la base de datos
                $producto = \App\Models\Producto::create([
                    'name' => $nuevoProducto['nombre'],
                    'categoria_id' => $nuevoProducto['categoria_id'],
                    'unidad_medida' => 'unidad', // Valor por defecto
                ]);
                
                // Guardar para vincular después
                $nuevosProductosCreados[$producto->id_producto] = [
                    'cantidad' => $nuevoProducto['cantidad']
                ];
            }
        }

        // 4. Vinculación de Productos existentes (attach)
        $productsToAttach = [];
        if ($request->has('productos')) {
            foreach ($request->productos as $productData) {
                $productsToAttach[$productData['producto_id']] = [
                    'cantidad' => $productData['cantidad']
                ];
            }
        }

        // Combinar productos existentes con los nuevos creados
        // Usamos + en lugar de array_merge para preservar las claves numéricas
        $allProductsToAttach = $productsToAttach + $nuevosProductosCreados;
        
        // Adjuntar todos los productos a la lista
        if (!empty($allProductsToAttach)) {
            $lista->productos()->attach($allProductsToAttach);
        }

        // 5. Redirección
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
            'email' => 'required|email|exists:users,email',
            'role' => 'required|in:viewer,editor'
        ]);

        $userToShare = \App\Models\User::where('email', $data['email'])->first();

        if ($userToShare) {
            // Verificar que no sea el propietario
            if ($userToShare->id === $lista->owner_id) {
                return back()->with('error', 'No puedes compartir la lista contigo mismo.');
            }

            // Evitar duplicados
            $lista->sharedUsers()->syncWithoutDetaching([
                $userToShare->id => ['role' => $data['role']]
            ]);
        }

        return back()->with('success', 'Lista compartida correctamente.');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lista $lista)
    {
        $user = Auth::user();
        
        // Verificar si es propietario
        $isOwner = $lista->owner_id === $user->id;
        
        // Si no es propietario, verificar si es editor
        if (!$isOwner) {
            $sharedUser = $lista->usuariosCompartidos()
                ->where('user_id', $user->id)
                ->first();
            
            // Si no está compartida con él o es lector, denegar acceso
            if (!$sharedUser || $sharedUser->pivot->role === 'lector') {
                abort(403, "No tienes permiso para editar esta lista.");
            }
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

        // 1. Autorización: verificar si es propietario o editor
        $isOwner = $lista->owner_id === $user->id;
        
        if (!$isOwner) {
            $sharedUser = $lista->usuariosCompartidos()
                ->where('user_id', $user->id)
                ->first();
            
            // Si no está compartida con él o es lector, denegar acceso
            if (!$sharedUser || $sharedUser->pivot->role === 'lector') {
                abort(403, "No tienes permiso para actualizar esta lista.");
            }
        }

        // 2. Validación de los datos
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'productos' => 'nullable|array',
            'productos.*.producto_id' => 'required|exists:productos,id_producto',
            'productos.*.cantidad' => 'required|integer|min:1',
            // Validar productos nuevos
            'nuevos_productos' => 'nullable|array',
            'nuevos_productos.*.nombre' => 'required|string|max:255',
            'nuevos_productos.*.categoria_id' => 'required|exists:categorias,id_categoria',
            'nuevos_productos.*.cantidad' => 'required|integer|min:1',
        ]);

        // 3. Actualización de la lista principal
        $lista->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        // 4. Procesar nuevos productos primero
        $nuevosProductosCreados = [];
        if ($request->has('nuevos_productos')) {
            foreach ($request->nuevos_productos as $nuevoProducto) {
                // Crear el producto en la base de datos
                $producto = \App\Models\Producto::create([
                    'name' => $nuevoProducto['nombre'],
                    'categoria_id' => $nuevoProducto['categoria_id'],
                    'unidad_medida' => 'unidad', // Valor por defecto
                ]);
                
                // Guardar para vincular después
                $nuevosProductosCreados[$producto->id_producto] = [
                    'cantidad' => $nuevoProducto['cantidad']
                ];
            }
        }

        // 5. Sincronización de Productos existentes
        $syncData = [];
        if ($request->has('productos')) {
            foreach ($request->productos as $productData) {
                $productoId = $productData['producto_id'];
                $cantidad = $productData['cantidad'];

                // Preparamos el array de sincronización: [producto_id => [pivot_data]]
                $syncData[$productoId] = ['cantidad' => $cantidad];
            }
        }

        // Combinar productos existentes con los nuevos creados
        // Usamos + en lugar de array_merge para preservar las claves numéricas
        $allProductsToSync = $syncData + $nuevosProductosCreados;

        // Sincronizar la relación Muchos a Muchos. Esto añade, actualiza o elimina productos.
        $lista->productos()->sync($allProductsToSync);

        // 6. Redirección
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

    /**
     * Cambiar el estado de 'comprado' de un producto en la lista.
     */
    public function alternarComprado(Request $request, Lista $lista)
    {
        $usuario = Auth::user();

        // Verificar que el usuario tenga acceso a la lista
        if (!$lista->userHasAccess($usuario->id)) {
            abort(403, "No tienes permiso para modificar esta lista.");
        }
        
        // Verificar que el usuario sea propietario o editor (NO lector)
        $isOwner = $lista->owner_id === $usuario->id;
        if (!$isOwner) {
            $sharedUser = $lista->usuariosCompartidos()
                ->where('user_id', $usuario->id)
                ->first();
            
            if (!$sharedUser || $sharedUser->pivot->role === 'lector') {
                abort(403, "No tienes permisos de edición en esta lista. Solo el propietario y editores pueden marcar productos.");
            }
        }

        // Validar que se reciba el producto_id
        $request->validate([
            'producto_id' => 'required|exists:productos,id_producto',
        ]);

        $productoId = $request->producto_id;

        // Obtener el producto de la lista con su estado actual
        $producto = $lista->productos()->where('item_lista.id_producto', $productoId)->first();

        if (!$producto) {
            return back()->with('error', 'Producto no encontrado en esta lista.');
        }

        // Cambiar el estado de 'comprado' (alternar)
        $nuevoEstado = !$producto->pivot->comprado;

        // Actualizar el estado en la tabla pivot
        $lista->productos()->updateExistingPivot($productoId, [
            'comprado' => $nuevoEstado
        ]);

        return back()->with('success', $nuevoEstado ? 'Producto marcado como comprado.' : 'Producto desmarcado.');
    }
}
