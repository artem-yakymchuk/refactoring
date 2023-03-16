<?php

declare(strict_types=1);

namespace App\Bin;

use App\Bin\Entity\BinLookup;
use App\Bin\Http\BinListClientInterface;
use App\Serializer\SerializerFactory;

final class BinLookupProvider implements BinLookupProviderInterface
{
    private BinListClientInterface $binListClient;
    private SerializerFactory $serializerFactory;

    public function __construct(BinListClientInterface $binListClient, SerializerFactory $serializerFactory)
    {
        $this->binListClient = $binListClient;
        $this->serializerFactory = $serializerFactory;
    }

    /**
     * @param string $bin
     * @return BinLookup
     * @throws Exception\BinLookupException
     */
    public function lookupBin(string $bin): BinLookup
    {
        $serializer = $this->serializerFactory->getSerializer();

        return $serializer->deserialize(
            $this->binListClient->request($bin)->getContent(),
            BinLookup::class,
            'json',
            ['disable_type_enforcement' => true]
        );
    }
}
