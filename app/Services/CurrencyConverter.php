<?php

namespace App\Services;

use App\Models\Setting;

class CurrencyConverter
{
    /**
     * Taux de change vers XAF (FCFA).
     * Ces taux peuvent être surchargés via les settings.
     */
    private static array $defaultRates = [
        'EUR' => 655.957,  // 1 EUR = 655.957 XAF (taux fixe CEMAC)
        'USD' => 600,
        'GBP' => 760,
        'JPY' => 4.1,
        'CNY' => 83,
        'XAF' => 1,
    ];

    public static function toXAF(float $amount, string $fromCurrency): float
    {
        $fromCurrency = strtoupper($fromCurrency);

        if ($fromCurrency === 'XAF') {
            return $amount;
        }

        // Check if a custom rate exists in settings
        $settingKey = 'rate_' . strtolower($fromCurrency) . '_xaf';
        $rate = Setting::get($settingKey);

        if (!$rate) {
            $rate = self::$defaultRates[$fromCurrency] ?? self::$defaultRates['EUR'];
        }

        return round($amount * (float) $rate, 0);
    }

    public static function getRate(string $fromCurrency): float
    {
        $fromCurrency = strtoupper($fromCurrency);
        $settingKey = 'rate_' . strtolower($fromCurrency) . '_xaf';
        $rate = Setting::get($settingKey);

        return (float) ($rate ?: (self::$defaultRates[$fromCurrency] ?? self::$defaultRates['EUR']));
    }
}
