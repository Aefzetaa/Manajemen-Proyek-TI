<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceType extends Model
{
    public const DEFAULT_MECHANIC_SHARE_PERCENT = 35;
    public const DEFAULT_CASHIER_SHARE_PERCENT = 15;

    protected $fillable = ['name', 'estimated_minutes', 'base_price', 'mechanic_salary', 'cashier_salary'];

    public function bookings()
    {
        return $this->belongsToMany(Booking::class);
    }

    public function promotions()
    {
        return $this->hasMany(Promotion::class);
    }

    public function activePromotions()
    {
        return $this->hasMany(Promotion::class)->active();
    }

    public function activePromotion(): ?Promotion
    {
        if (! array_key_exists('resolved_active_promotion', $this->relations)) {
            $specific = Promotion::active()
                ->where('service_type_id', $this->id)
                ->orderByDesc('discount_percent')
                ->latest()
                ->first();

            $fallback = $specific ?: Promotion::active()
                ->whereNull('service_type_id')
                ->orderByDesc('discount_percent')
                ->latest()
                ->first();

            $this->setRelation('resolved_active_promotion', $fallback);
        }

        return $this->relations['resolved_active_promotion'];
    }

    public function discountPercent(): int
    {
        return (int) ($this->activePromotion()?->discount_percent ?? 0);
    }

    public function discountedPrice(): int
    {
        $discount = $this->discountPercent();

        if ($discount <= 0) {
            return (int) $this->base_price;
        }

        return max(0, (int) round($this->base_price * (100 - $discount) / 100));
    }

    public function hasActiveDiscount(): bool
    {
        return $this->discountPercent() > 0 && $this->discountedPrice() < $this->base_price;
    }

    public function mechanicSharePercent(): int
    {
        return $this->sharePercents()['mechanic'];
    }

    public function cashierSharePercent(): int
    {
        return $this->sharePercents()['cashier'];
    }

    /**
     * Legacy column names are reused as share percentages to avoid a risky migration.
     *
     * @return array{mechanic: int, cashier: int}
     */
    public function sharePercents(): array
    {
        $mechanic = (int) $this->mechanic_salary;
        $cashier = (int) $this->cashier_salary;
        $total = $mechanic + $cashier;

        if ($mechanic < 0 || $cashier < 0 || $total < 50 || $total > 100) {
            return [
                'mechanic' => self::DEFAULT_MECHANIC_SHARE_PERCENT,
                'cashier' => self::DEFAULT_CASHIER_SHARE_PERCENT,
            ];
        }

        return [
            'mechanic' => $mechanic,
            'cashier' => $cashier,
        ];
    }
}
