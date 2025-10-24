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
        // 1. Validación de los datos principales y anidados
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            // Validación de la estructura de categorías y productos
            'categorias' => 'nullable|array',
            'categorias.*.name' => 'required_with:categorias|string|max:255',
            'categorias.*.productos' => 'nullable|array',
            'categorias.*.productos.*.name' => 'required_with:categorias.*.productos|string|max:255',
            'categorias.*.productos.*.cantidad' => 'required_with:categorias.*.productos|integer|min:1',
        ]);

        // 2. Crear la Lista principal
        $lista = Lista::create([
            'name' => $request->name,
            'description' => $request->description,
            'owner_id' => Auth::id(),
        ]);

        // 3. Procesar las Categorías y Productos
        if ($request->has('categorias')) {
            foreach ($request->categorias as $catData) {
                // Crear la Categoría
                $categoria = $lista->categorias()->create([
                    'name' => $catData['name'],
                ]);

                // Procesar los Productos de esta Categoría
                if (!empty($catData['productos'])) {
                    $productosData = [];
                    foreach ($catData['productos'] as $prodData) {
                        // Preparamos los datos para la inserción masiva (createMany)
                        $productosData[] = [
                            'name' => $prodData['name'],
                            'cantidad' => $prodData['cantidad'],
                            'created_at' => now(), // Aseguramos que los timestamps se guarden
                            'updated_at' => now(),
                        ];
                    }
                    // Insertamos todos los productos de la categoría
                    $categoria->productos()->createMany($productosData);
                }
            }
        }

        // 4. Redirección
        return redirect()->route('listas.show', $lista)
            ->with('success', 'Lista creada exitosamente con sus categorías y productos.');
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

        // Autorización: Solo el propietario puede editar la lista principal (o si tienes roles de editor definidos)
        if ($lista->owner_id !== $user->id) {
            abort(403, "No tienes permiso para editar esta lista.");
        }

        // Simplemente pasamos el objeto lista a la vista
        return view('listas.edit', compact('lista'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lista $lista)
    {
        $user = Auth::user();

        // Autorización (re-verificación)
        if ($lista->owner_id !== $user->id) {
            abort(403, "No tienes permiso para actualizar esta lista.");
        }

        // 1. Validación de los datos
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'categorias' => 'nullable|array',
            // Si tiene 'id', es una categoría existente; si no, es nueva (temp_)
            'categorias.*.id' => 'nullable|string',
            'categorias.*.name' => 'required_with:categorias|string|max:255',

            'categorias.*.productos' => 'nullable|array',
            'categorias.*.productos.*.id' => 'nullable|string',
            'categorias.*.productos.*.name' => 'required_with:categorias.*.productos|string|max:255',
            'categorias.*.productos.*.cantidad' => 'required_with:categorias.*.productos|integer|min:1',
            // Nota: Los campos de imagen deben ser manejados en otro controlador (ProductoController) o con un Request diferente
        ]);

        // 2. Actualización de la lista principal
        $lista->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        // --- Procesamiento de Categorías y Productos ---

        $incomingCategoryIds = [];

        if ($request->has('categorias')) {
            foreach ($request->categorias as $catData) {

                // Determinar si es una categoría existente o nueva (basado en el ID)
                $isNewCategory = str_starts_with($catData['id'] ?? '', 'temp_');

                if ($isNewCategory) {
                    // CREAR nueva categoría
                    $categoria = $lista->categorias()->create(['name' => $catData['name']]);
                } else {
                    // ACTUALIZAR categoría existente
                    $categoria = $lista->categorias()->findOrFail($catData['id']);
                    $categoria->update(['name' => $catData['name']]);
                }

                $incomingCategoryIds[] = $categoria->id;

                // Procesar Productos
                $incomingProductIds = [];

                if (!empty($catData['productos'])) {
                    foreach ($catData['productos'] as $prodData) {

                        // Determinar si es un producto existente o nuevo
                        $isNewProduct = str_starts_with($prodData['id'] ?? '', 'temp_');

                        if ($isNewProduct) {
                            // CREAR nuevo producto (sin imagen por ahora, se deja para la vista show/edit de producto)
                            $producto = $categoria->productos()->create([
                                'name' => $prodData['name'],
                                'cantidad' => $prodData['cantidad'],
                            ]);
                        } else {
                            // ACTUALIZAR producto existente
                            $producto = $categoria->productos()->findOrFail($prodData['id']);
                            $producto->update([
                                'name' => $prodData['name'],
                                'cantidad' => $prodData['cantidad'],
                            ]);
                        }
                        $incomingProductIds[] = $producto->id;
                    }
                }

                // ELIMINAR productos que no fueron enviados en el request
                $categoria->productos()->whereNotIn('id', $incomingProductIds)->delete();
            }
        }

        // ELIMINAR categorías que no fueron enviadas en el request
        $lista->categorias()->whereNotIn('id', $incomingCategoryIds)->delete();


        // 3. Redirección y mensaje de éxito
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
