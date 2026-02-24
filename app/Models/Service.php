<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'active',
    ];

    public function workOrders(): BelongsToMany
    {
        return $this->belongsToMany(WorkOder::class, 'work_order_services')
            ->withPivot('quantity', 'unit_price')
            ->withTimestamps();
    }
}
