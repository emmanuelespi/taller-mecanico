<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkOrderHistory extends Model
{
    protected $table = 'work_order_histories';

    protected $fillable = [
        'work_order_id',
        'user_id',
        'from_status',
        'to_status',
        'notes',
    ];

    protected $casts = [
        'from_status' => \App\Enums\WorkOrderStatus::class,
        'to_status' => \App\Enums\WorkOrderStatus::class,
    ];

    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
