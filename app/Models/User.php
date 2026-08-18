<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'deleted_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function workOrders(): HasMany
    {
        return $this->hasMany(WorkOrder::class, 'user_id');
    }

    public function assignedWorkOrders(): HasMany
    {
        return $this->hasMany(WorkOrder::class, 'mechanic_id');
    }

    public function inventoryMovements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isMechanic(): bool
    {
        return $this->role === 'mecanico';
    }

    public function isReceptionist(): bool
    {
        return $this->role === 'recepcionista';
    }

    public function isActive(): bool
    {
        return $this->is_active && !$this->trashed();
    }

    public static function getRoles(): array
    {
        return [
            'admin' => 'Administrador',
            'recepcionista' => 'Recepcionista',
            'mecanico' => 'Mecánico',
        ];
    }

    public function getRoleName(): string
    {
        return self::getRoles()[$this->role] ?? $this->role;
    }
}
