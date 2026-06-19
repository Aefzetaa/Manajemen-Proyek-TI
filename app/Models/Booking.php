<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    public const STATUSES = [
        'scheduled'   => 'Dijadwalkan',
        'accepted'    => 'Belum Dikerjakan',
        'in_progress' => 'Dikerjakan',
        'completed'   => 'Selesai',
        'canceled'    => 'Dibatalkan',
    ];

    protected $fillable = [
        'user_id',
        'vehicle_id',
        'mechanic_id',
        'booking_date',
        'booking_time',
        'status',
        'service_description',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'booking_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function serviceTypes()
    {
        return $this->belongsToMany(ServiceType::class, 'booking_service_type');
    }

    public function mechanic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mechanic_id');
    }

    public function serviceOrder(): HasOne
    {
        return $this->hasOne(ServiceOrder::class);
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }
}
