<?php

namespace Modules\Superadmin\Services;

use App\Business;
use Carbon\Carbon;
use Modules\Superadmin\Entities\Package;

class SubscriptionPricingService
{
    public function resolve(Business $business, Package $package, ?Carbon $periodStart = null): array
    {
        $periodStart = ($periodStart ? $periodStart->copy() : Carbon::now('UTC'));
        $baseAmount = $this->baseAmount($business, $package, $periodStart);
        $vatRate = (float) ($package->vat ?? 0);
        $vatAmount = round($baseAmount * ($vatRate / 100), 2);

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

    /**
     * Promotion cutoff dates are business-financial facts, not display
     * values, so they must be anchored to UTC regardless of which
     * business's local timezone happens to be active for this request
     * (see Timezone middleware, which calls date_default_timezone_set()
     * per business). periodStart (derived from Stripe timestamps) is
     * always UTC, so comparing it against a non-UTC promotion end date
     * can shift month-boundary decisions by the timezone's offset.
     */
    public function promotionEnd(Business $business): ?Carbon
    {
        return match ($business->business_type) {
            'company' => Carbon::create(2027, 1, 1, 0, 0, 0, 'UTC'),
            'self_employed' => Carbon::create(2027, 6, 1, 0, 0, 0, 'UTC'),
            default => null,
        };
    }

    protected function baseAmount(Business $business, Package $package, Carbon $periodStart): float
    {
        if ($package->interval === 'months' || $package->interval === 'years') {
            $periodEnd = $this->periodEnd($periodStart, $package);
            $periodMonths = $periodStart->diffInMonths($periodEnd);
            $monthlyPackageAmount = (float) $package->price / max(1, $periodMonths);
            $promotionEnd = $this->promotionEnd($business);
            $baseAmount = 0.0;
            $month = $periodStart->copy()->utc()->startOfMonth();

            for ($monthNumber = 0; $monthNumber < $periodMonths; $monthNumber++) {
                $isPromotionalMonth = $promotionEnd !== null
                    && $month->lt($promotionEnd)
                    && $month->copy()->endOfMonth()->gte($periodStart);

                $baseAmount += $isPromotionalMonth
                    ? 1.00
                    : $monthlyPackageAmount;

                $month->addMonthNoOverflow();
            }

            return $baseAmount;
        }

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
