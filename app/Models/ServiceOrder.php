<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ServiceOrder extends Model
{
    public const STATUSES = [
        'waiting_cashier'  => 'Menunggu Kasir',
        'waiting_approval' => 'Menunggu Persetujuan',
        'approved'         => 'Disetujui Pelanggan',
        'finished'         => 'Selesai',
        'canceled'         => 'Dibatalkan',
    ];

    protected $fillable = [
        'booking_id',
        'customer_id',
        'mechanic_id',
        'vehicle_id',
        'service_type_id',
        'serviced_at',
        'complaint',
        'diagnosis',
        'labor_cost',
        'parts_total',
        'total_cost',
        'status',
        'customer_approved_at',
    ];

    protected function casts(): array
    {
        return [
            'serviced_at' => 'date',
            'customer_approved_at' => 'datetime',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function mechanic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mechanic_id');
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function serviceType(): BelongsTo
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ServiceOrderItem::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    /**
     * @return array{mechanic: int, cashier: int, owner_labor: int, owner_total: int}
     */
    public function laborShareBreakdown(): array
    {
        $laborCost = max(0, (int) $this->labor_cost);
        $partsTotal = max(0, (int) $this->parts_total);

        if ($laborCost <= 0) {
            return [
                'mechanic' => 0,
                'cashier' => 0,
                'owner_labor' => 0,
                'owner_total' => $partsTotal,
            ];
        }

        $this->loadMissing('booking.serviceTypes.activePromotions');
        $serviceTypes = $this->booking?->serviceTypes ?? collect();

        if ($serviceTypes->isEmpty()) {
            $mechanic = (int) round($laborCost * ServiceType::DEFAULT_MECHANIC_SHARE_PERCENT / 100);
            $cashier = (int) round($laborCost * ServiceType::DEFAULT_CASHIER_SHARE_PERCENT / 100);
            $ownerLabor = max(0, $laborCost - $mechanic - $cashier);

            return [
                'mechanic' => $mechanic,
                'cashier' => $cashier,
                'owner_labor' => $ownerLabor,
                'owner_total' => $ownerLabor + $partsTotal,
            ];
        }

        $weightedServices = $serviceTypes
            ->map(fn (ServiceType $type) => [
                'type' => $type,
                'weight' => max(0, (int) $type->discountedPrice()),
            ])
            ->values();

        $weightTotal = $weightedServices->sum('weight');
        if ($weightTotal <= 0) {
            $weightedServices = $weightedServices
                ->map(fn (array $item) => [...$item, 'weight' => 1])
                ->values();
            $weightTotal = $weightedServices->count();
        }

        $mechanic = 0;
        $cashier = 0;
        $remainingLabor = $laborCost;
        $lastIndex = $weightedServices->count() - 1;

        foreach ($weightedServices as $index => $item) {
            /** @var ServiceType $type */
            $type = $item['type'];
            $portion = $index === $lastIndex
                ? $remainingLabor
                : (int) round($laborCost * $item['weight'] / $weightTotal);

            $remainingLabor -= $portion;
            $mechanic += (int) round($portion * $type->mechanicSharePercent() / 100);
            $cashier += (int) round($portion * $type->cashierSharePercent() / 100);
        }

        $ownerLabor = max(0, $laborCost - $mechanic - $cashier);

        return [
            'mechanic' => $mechanic,
            'cashier' => $cashier,
            'owner_labor' => $ownerLabor,
            'owner_total' => $ownerLabor + $partsTotal,
        ];
    }
}
