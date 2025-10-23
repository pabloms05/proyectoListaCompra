<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Lista extends Model
{
    protected $fillable = [
        'name', 'description', 'owner_id'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function categorias()
    {
        return $this->hasMany(Categoria::class);
    }

    public function sharedUsers()
    {
        return $this->belongsToMany(User::class, 'lista_user')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    public function userHasAccess($userId)
    {
        if ($this->owner_id === $userId) {
            return true;
        }
        return $this->sharedUsers()->where('user_id', $userId)->exists();
    }
}
