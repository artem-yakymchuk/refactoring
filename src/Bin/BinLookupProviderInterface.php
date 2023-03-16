<?php

declare(strict_types=1);

namespace App\Bin;

use App\Bin\Entity\BinLookup;

interface BinLookupProviderInterface
{
    /**
     * @param string $bin
     * @return BinLookup
     * @throws Exception\BinLookupException
     */
    public function lookupBin(string $bin): BinLookup;
}
