<?php

declare(strict_types=1);

namespace App\Bin;

use App\Bin\Entity\Country;

final class GetCountryCommissionMultiplier implements GetCountryComissionMultiplierInterface
{
    private const EUROPE_COUNTRY_CODES = [
        'AT',
        'BE',
        'BG',
        'CY',
        'CZ',
        'DE',
        'DK',
        'EE',
        'ES',
        'FI',
        'FR',
        'GR',
        'HR',
        'HU',
        'IE',
        'IT',
        'LT',
        'LU',
        'LV',
        'MT',
        'NL',
        'PO',
        'PT',
        'RO',
        'SE',
        'SI',
        'SK',
    ];

    public function execute(Country $country): float
    {
        return in_array($country->getAlpha2(), self::EUROPE_COUNTRY_CODES, true) ? 0.01 : 0.02;
    }
}
