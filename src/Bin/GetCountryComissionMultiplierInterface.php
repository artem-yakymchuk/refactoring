<?php

namespace App\Bin;

use App\Bin\Entity\Country;

interface GetCountryComissionMultiplierInterface
{
    public function execute(Country $country): float;
}
