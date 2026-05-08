<?php

namespace App\Policies;

use App\Models\Lista;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ListaPolicy
{
    /**
     * Determina si el usuario puede ver la lista
     */
    public function ver(User $usuario, Lista $lista): bool
    {
        // Es el propietario O tiene acceso compartido
        return $lista->owner_id === $usuario->id || 
               $lista->usuariosCompartidos->contains($usuario->id);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Lista $lista): bool
    {
        return $this->ver($user, $lista);
    }

    /**
     * Determina si el usuario puede editar la lista
     */
    public function actualizar(User $usuario, Lista $lista): bool
    {
        // Es el propietario O es editor
        if ($lista->owner_id === $usuario->id) {
            return true;
        }

        $compartido = $lista->usuariosCompartidos()
            ->where('user_id', $usuario->id)
            ->first();

        return $compartido && $compartido->pivot->role === 'editor';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Lista $lista): bool
    {
        return $this->actualizar($user, $lista);
    }

    /**
     * Determina si el usuario puede eliminar la lista
     */
    public function eliminar(User $usuario, Lista $lista): bool
    {
        // Solo el propietario
        return $lista->owner_id === $usuario->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Lista $lista): bool
    {
        return $this->eliminar($user, $lista);
    }

    /**
     * Determina si el usuario puede compartir la lista
     */
    public function compartir(User $usuario, Lista $lista): bool
    {
        // Solo el propietario
        return $lista->owner_id === $usuario->id;
    }

    /**
     * Determina si el usuario es el propietario
     */
    public function esPropietario(User $usuario, Lista $lista): bool
    {
        return $lista->owner_id === $usuario->id;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Lista $lista): bool
    {
        return $lista->owner_id === $user->id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Lista $lista): bool
    {
        return $lista->owner_id === $user->id;
    }
}
