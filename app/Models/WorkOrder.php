<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class WorkOrder extends Model
{
    protected $table = 'work_orders';

    protected $fillable = [
        'order_number',
        'client_id',
        'vehicle_id',
        'user_id',
        'mechanic_id',
        'status',
        'problem_description',
        'diagnosis',
        'observations',
        'subtotal',
        'tax',
        'total',
        'payment_status',
        'payment_method',
        'entry_date',
        'delivery_date',
        'completed_at',
        'delivered_at',
    ];

    protected $casts = [
        'entry_date' => 'datetime',
        'delivery_date' => 'datetime',
        'completed_at' => 'datetime',
        'delivered_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function mechanic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mechanic_id');
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'work_order_services')
            ->withPivot('quantity', 'unit_price')
            ->withTimestamps();
    }

    public function spareParts(): BelongsToMany
    {
        return $this->belongsToMany(SparePart::class, 'work_order_spare_parts')
            ->withPivot('quantity', 'unit_price')
            ->withTimestamps();
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeComplete($query)
    {
        return $query->where('status', ' complete');
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Pendiente',
            'in_progress' => 'En Progreso',
            'completed' => 'Completado',
            'delivered' => 'Entregado',
            'cancelled' => 'Cancelado',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'yellow',
            'in_progress' => 'blue',
            'completed' => 'green',
            'delivered' => 'gray',
            'cancelled' => 'red',
            default => 'gray',
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-yellow-500/10 text-yellow-400',
            'in_progress' => 'bg-blue-500/10 text-blue-400',
            'completed' => 'bg-green-500/10 text-green-400',
            'delivered' => 'bg-gray-500/10 text-gray-400',
            'cancelled' => 'bg-red-500/10 text-red-400',
            default => 'bg-gray-500/10 text-gray-400',
        };
    }

    public function getFormattedTotalAttribute(): string
    {
        return '$'.number_format($this->total, 2);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isDelivered(): bool
    {
        return $this->status === 'delivered';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function canBeEdited(): bool
    {
        return ! in_array($this->status, ['completed', 'delivered', 'cancelled']);
    }

    public function calculateTotals(): void
    {
        $subtotal = 0;

        foreach ($this->services as $service) {
            $subtotal += $service->pivot->unit_price * $service->pivot->quantity;
        }

        foreach ($this->spareParts as $part) {
            $subtotal += $part->pivot->unit_price * $part->pivot->quantity;
        }

        $tax = $subtotal * 0.16;
        $total = $subtotal + $tax;

        $this->update([
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
        ]);
    }
}
