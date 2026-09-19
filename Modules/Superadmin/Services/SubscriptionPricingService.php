<?php

namespace Modules\Superadmin\Services;

use App\Business;
use Carbon\Carbon;
use Modules\Superadmin\Entities\Package;

class SubscriptionPricingService
{
    public function resolve(Business $business, Package $package, ?Carbon $periodStart = null): array
    {
        $periodStart = ($periodStart ?: now())->copy();
        $baseAmount = $this->baseAmount($business, $package, $periodStart);
        $vatAmount = round($baseAmount * ((float) $package->vat / 100), 2);

        return [
            'base_amount' => round($baseAmount, 2),
            'vat_amount' => $vatAmount,
            'total_amount' => round($baseAmount + $vatAmount, 2),
            'currency' => strtolower((string) optional($business->currency)->code),
            'period_start' => $periodStart,
            'period_end' => $this->periodEnd($periodStart, $package),
            'promotion_applied' => $this->promotionApplies($business, $periodStart),
        ];
    }

    public function proratedAmount(float $amount, Carbon $periodStart, Carbon $periodEnd, Carbon $at): float
    {
        $totalSeconds = max(1, $periodStart->diffInSeconds($periodEnd));
        $remainingSeconds = max(0, min($totalSeconds, $at->diffInSeconds($periodEnd)));

        return round($amount * ($remainingSeconds / $totalSeconds), 2);
    }

    public function promotionEnd(Business $business): ?Carbon
    {
        return match ($business->business_type) {
            'company' => Carbon::create(2027, 1, 1)->startOfDay(),
            'self_employed' => Carbon::create(2027, 6, 1)->startOfDay(),
            default => null,
        };
    }

    protected function baseAmount(Business $business, Package $package, Carbon $periodStart): float
    {
        return $this->promotionApplies($business, $periodStart)
            ? 1.00
            : (float) $package->price;
    }

    protected function promotionApplies(Business $business, Carbon $periodStart): bool
    {
        $promotionEnd = $this->promotionEnd($business);

        return $promotionEnd !== null && $periodStart->lt($promotionEnd);
    }

    protected function periodEnd(Carbon $periodStart, Package $package): Carbon
    {
        return match ($package->interval) {
            'days' => $periodStart->copy()->addDays((int) $package->interval_count),
            'months' => $periodStart->copy()->addMonthsNoOverflow((int) $package->interval_count),
            'years' => $periodStart->copy()->addYearsNoOverflow((int) $package->interval_count),
            default => throw new \InvalidArgumentException('Unsupported package billing interval.'),
        };
    }
}
