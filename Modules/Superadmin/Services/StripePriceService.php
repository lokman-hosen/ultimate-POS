<?php

namespace Modules\Superadmin\Services;

use Stripe\Price;
use Stripe\Stripe;
use Modules\Superadmin\Entities\Package;

class StripePriceService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret_key'));
    }

    public function forRecurringPackage(Package $package, array $pricing): string
    {
        $currency = $pricing['currency'] ?: strtolower((string) optional(\App\System::getCurrency())->code);
        $amount = $this->minorAmount($pricing['total_amount'], $currency);
        [$interval, $intervalCount] = $this->stripeInterval($package);

        $existing = \DB::table('stripe_package_prices')
            ->where('package_id', $package->id)
            ->where('currency', $currency)
            ->where('unit_amount', $amount)
            ->where('interval', $interval)
            ->where('interval_count', $intervalCount)
            ->value('stripe_price_id');

        if ($existing) {
            return $existing;
        }

        $price = Price::create([
            'currency' => $currency,
            'unit_amount' => $amount,
            'recurring' => [
                'interval' => $interval,
                'interval_count' => $intervalCount,
            ],
            'product_data' => [
                'name' => $package->name,
                'metadata' => ['package_id' => (string) $package->id],
            ],
            'metadata' => [
                'package_id' => (string) $package->id,
                'base_amount' => (string) $pricing['base_amount'],
                'vat_amount' => (string) $pricing['vat_amount'],
            ],
        ]);

        \DB::table('stripe_package_prices')->insert([
            'package_id' => $package->id,
            'stripe_price_id' => $price->id,
            'currency' => $currency,
            'unit_amount' => $amount,
            'interval' => $interval,
            'interval_count' => $intervalCount,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $price->id;
    }

    protected function minorAmount(float $amount, string $currency): int
    {
        $zeroDecimal = ['bif', 'clp', 'djf', 'gnf', 'jpy', 'kmf', 'krw', 'mga', 'pyg', 'rwf', 'ugx', 'vnd', 'vuv', 'xaf', 'xof', 'xpf'];

        return in_array($currency, $zeroDecimal, true)
            ? (int) round($amount)
            : (int) round($amount * 100);
    }

    protected function stripeInterval(Package $package): array
    {
        return match ($package->interval) {
            'days' => $package->interval_count <= 7
                ? ['day', (int) $package->interval_count]
                : throw new \InvalidArgumentException('Stripe supports a maximum recurring interval of 7 days.'),
            'months' => ['month', (int) $package->interval_count],
            'years' => ['year', (int) $package->interval_count],
            default => throw new \InvalidArgumentException('Unsupported package billing interval.'),
        };
    }
}
