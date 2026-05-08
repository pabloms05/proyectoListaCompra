<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lista extends Model
{
    use HasFactory;

    protected $table = 'listas';
    protected $primaryKey = 'id_lista';

    protected $fillable = [
        'owner_id',
        'name',
        'description',
        'compartida',
    ];

    protected $appends = ['total', 'total_comprado', 'total_pendiente'];

    // 🧍‍♂️ Usuario creador
    public function creador()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    // 👥 Usuarios con acceso compartido
    public function usuariosCompartidos()
    {
        return $this->belongsToMany(User::class, 'lista_user', 'id_lista', 'user_id')
            ->withPivot('role')
            ->withTimestamps();
    }

    // Alias para compatibilidad
    public function sharedUsers()
    {
        return $this->usuariosCompartidos();
    }

    // 🛒 Productos (a través de items)
    public function items()
    {
        return $this->hasMany(ItemLista::class, 'id_lista');
    }
    
    public function productos()
    {
        return $this->belongsToMany(
            Producto::class,
            'item_lista',
            'id_lista',
            'id_producto'
        )->withPivot('cantidad', 'comprado', 'notas', 'precio_unitario')->withTimestamps();
    }

    /**
     * Verifica si un usuario tiene acceso a esta lista
     * (ya sea como propietario o como usuario con quien se compartió)
     */
    public function userHasAccess($userId)
    {
        // Si es el propietario
        if ($this->owner_id === $userId) {
            return true;
        }

        // Si está en la lista de usuarios compartidos
        return $this->sharedUsers()->where('user_id', $userId)->exists();
    }
    
    // 💰 Calcular total de la lista
    public function getTotalAttribute()
    {
        return $this->items->sum(function($item) {
            return ($item->precio_unitario ?? 0) * ($item->cantidad ?? 1);
        });
    }
    
    // 💰 Total de productos comprados
    public function getTotalCompradoAttribute()
    {
        return $this->items->where('comprado', true)->sum(function($item) {
            return ($item->precio_unitario ?? 0) * ($item->cantidad ?? 1);
        });
    }
    
    // 💰 Total de productos pendientes
    public function getTotalPendienteAttribute()
    {
        return $this->items->where('comprado', false)->sum(function($item) {
            return ($item->precio_unitario ?? 0) * ($item->cantidad ?? 1);
        });
    }
}
