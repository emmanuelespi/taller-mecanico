<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkOrder extends Model
{
    protected $fillable = [
        'client_id',
        'vehicle_id',
        'user_id',
        'mechanic_id',
        'status',
        'problem_description',
        'observations',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function receptionist(): BelongsTo
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

    public function getTotalAttribute(): float
    {
        $services = $this->services->sum(fn ($s) => $s->pivot->quantity * $s->pivot->unit_price);
        $spareParts = $this->spareParts->sum(fn ($s) => $s->pivot->quantity * $s->pivot->unit_price);

        return $services + $spareParts;
    }
}
