<?php

declare(strict_types=1);

namespace App\Tests\Bin;

use App\Bin\Entity\Country;
use App\Bin\GetCountryCommissionMultiplier;
use PHPUnit\Framework\TestCase;

class GetCountryCommissionMultiplierTest extends TestCase
{
    public function testExecuteReturnsCorrectMultiplierForEuropeanCountries(): void
    {
        $europeanCountryCode = 'DE';
        $europeanCountry = new Country($europeanCountryCode);

        $getCountryCommissionMultiplier = new GetCountryCommissionMultiplier();
        $multiplier = $getCountryCommissionMultiplier->execute($europeanCountry);

        $this->assertEquals(0.01, $multiplier);
    }

    public function testExecuteReturnsCorrectMultiplierForNonEuropeanCountries(): void
    {
        $nonEuropeanCountryCode = 'US';
        $nonEuropeanCountry = new Country($nonEuropeanCountryCode);

        $getCountryCommissionMultiplier = new GetCountryCommissionMultiplier();
        $multiplier = $getCountryCommissionMultiplier->execute($nonEuropeanCountry);

        $this->assertEquals(0.02, $multiplier);
    }
}
