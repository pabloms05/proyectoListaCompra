<?php

namespace App\Http\Controllers;

use App\Models\Lista;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CompartirListaController extends Controller
{
    /**
     * Buscar usuarios por nombre o email
     */
    public function buscarUsuarios(Request $request)
    {
        $busqueda = $request->input('q');
        
        if (strlen($busqueda) < 2) {
            return response()->json([]);
        }

        $usuarios = User::where('name', 'LIKE', "%{$busqueda}%")
            ->orWhere('email', 'LIKE', "%{$busqueda}%")
            ->where('id', '!=', Auth::id()) // Excluir usuario actual
            ->select('id', 'name', 'email')
            ->limit(10)
            ->get();

        return response()->json($usuarios);
    }

    /**
     * Compartir una lista con otro usuario
     */
    public function compartir(Request $request, $id)
    {
        $request->validate([
            'identificador_usuario' => 'required|string',
            'rol' => 'required|in:editor,lector'
        ]);

        $lista = Lista::findOrFail($id);

        // Verificar que el usuario actual es el propietario
        if ($lista->owner_id !== Auth::id()) {
            return response()->json([
                'exito' => false,
                'mensaje' => 'No tienes permisos para compartir esta lista'
            ], 403);
        }

        // Buscar el usuario por email o nombre
        $usuarioDestino = User::where('email', $request->identificador_usuario)
            ->orWhere('name', $request->identificador_usuario)
            ->first();

        if (!$usuarioDestino) {
            return response()->json([
                'exito' => false,
                'mensaje' => 'Usuario no encontrado'
            ], 404);
        }

        // Verificar que no es el mismo propietario
        if ($usuarioDestino->id === Auth::id()) {
            return response()->json([
                'exito' => false,
                'mensaje' => 'No puedes compartir la lista contigo mismo'
            ], 400);
        }

        // Verificar si ya está compartido
        $yaCompartido = $lista->usuariosCompartidos()
            ->where('user_id', $usuarioDestino->id)
            ->exists();

        if ($yaCompartido) {
            return response()->json([
                'exito' => false,
                'mensaje' => 'Esta lista ya está compartida con este usuario'
            ], 400);
        }

        // Compartir la lista
        $lista->usuariosCompartidos()->attach($usuarioDestino->id, [
            'role' => $request->rol,
            'estado' => 'aceptado'
        ]);

        // Marcar la lista como compartida
        if (!$lista->compartida) {
            $lista->compartida = true;
            $lista->save();
        }

        return response()->json([
            'exito' => true,
            'mensaje' => 'Lista compartida exitosamente',
            'usuario' => [
                'id' => $usuarioDestino->id,
                'nombre' => $usuarioDestino->name,
                'email' => $usuarioDestino->email,
                'rol' => $request->rol
            ]
        ]);
    }

    /**
     * Revocar acceso a un usuario
     */
    public function revocar($idLista, $idUsuario)
    {
        $lista = Lista::findOrFail($idLista);

        // Verificar que el usuario actual es el propietario
        if ($lista->owner_id !== Auth::id()) {
            return response()->json([
                'exito' => false,
                'mensaje' => 'No tienes permisos para revocar acceso a esta lista'
            ], 403);
        }

        // Revocar acceso
        $eliminados = $lista->usuariosCompartidos()->detach($idUsuario);

        if ($eliminados) {
            // Si no quedan usuarios compartidos, marcar como no compartida
            if ($lista->usuariosCompartidos()->count() === 0) {
                $lista->compartida = false;
                $lista->save();
            }

            return response()->json([
                'exito' => true,
                'mensaje' => 'Acceso revocado exitosamente'
            ]);
        }

        return response()->json([
            'exito' => false,
            'mensaje' => 'No se pudo revocar el acceso'
        ], 400);
    }

    /**
     * Actualizar el rol de un usuario compartido
     */
    public function actualizarRol(Request $request, $idLista, $idUsuario)
    {
        $request->validate([
            'rol' => 'required|in:editor,lector'
        ]);

        $lista = Lista::findOrFail($idLista);

        // Verificar que el usuario actual es el propietario
        if ($lista->owner_id !== Auth::id()) {
            return response()->json([
                'exito' => false,
                'mensaje' => 'No tienes permisos para modificar esta lista'
            ], 403);
        }

        // Actualizar el rol
        $actualizado = $lista->usuariosCompartidos()->updateExistingPivot($idUsuario, [
            'role' => $request->rol
        ]);

        if ($actualizado) {
            return response()->json([
                'exito' => true,
                'mensaje' => 'Rol actualizado exitosamente'
            ]);
        }

        return response()->json([
            'exito' => false,
            'mensaje' => 'No se pudo actualizar el rol'
        ], 400);
    }

    /**
     * Obtener listas compartidas conmigo
     */
    public function compartidasConmigo()
    {
        $usuario = Auth::user();
        
        $listasCompartidas = $usuario->sharedLists()
            ->with('creador:id,name,email') // 'creador' es la relación en el modelo Lista
            ->get()
            ->map(function($lista) {
                // Verificar que la relación creador existe
                $propietario = $lista->creador ?? $lista->owner;
                
                return [
                    'id' => $lista->id_lista,
                    'nombre' => $lista->name,
                    'descripcion' => $lista->description,
                    'rol' => $lista->pivot->role ?? 'lector',
                    'fecha_compartida' => $lista->pivot->created_at,
                    'propietario' => [
                        'id' => $propietario->id,
                        'nombre' => $propietario->name,
                        'email' => $propietario->email
                    ]
                ];
            });

        return response()->json($listasCompartidas);
    }

    /**
     * Obtener usuarios con acceso a una lista
     */
    public function obtenerUsuariosCompartidos($id)
    {
        $lista = Lista::findOrFail($id);

        // Verificar que el usuario actual es el propietario
        if ($lista->owner_id !== Auth::id()) {
            return response()->json([
                'exito' => false,
                'mensaje' => 'No tienes permisos para ver esta información'
            ], 403);
        }

        $usuariosCompartidos = $lista->usuariosCompartidos()
            ->select('users.id', 'users.name', 'users.email')
            ->get()
            ->map(function($usuario) {
                return [
                    'id' => $usuario->id,
                    'nombre' => $usuario->name,
                    'email' => $usuario->email,
                    'rol' => $usuario->pivot->role,
                    'fecha_compartida' => $usuario->pivot->created_at
                ];
            });

        return response()->json($usuariosCompartidos);
    }
};