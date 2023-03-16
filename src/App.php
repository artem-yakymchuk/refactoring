<?php

declare(strict_types=1);

namespace App;

use App\Entity\Operation;
use App\Serializer\SerializerFactory;
use SplFileObject;

final class App
{
    private SerializerFactory $serializerFactory;
    private CommissionCalculator $commissionCalculator;

    public function __construct(SerializerFactory $serializerFactory, CommissionCalculator $commissionCalculator)
    {
        $this->serializerFactory = $serializerFactory;
        $this->commissionCalculator = $commissionCalculator;
    }

    /**
     * @param string $fileName
     * @throws Bin\Exception\BinLookupException
     * @throws Rate\Exception\GetExchangeRatesRequestException
     * @throws Rate\Exception\MissingExchangeRateException
     */
    public function execute(string $fileName): void
    {
        $serializer = $this->serializerFactory->getSerializer();

        $file = new SplFileObject($fileName);

        $operations = [];
        while (!$file->eof()) {
            $line = $file->fgets();
            if (!$line) {
                continue;
            }
            $operations[] = $serializer->deserialize(
                $line,
                Operation::class,
                'json',
                ['disable_type_enforcement' => true]
            );
        }
        $file = null;

        $operationsWithCommissions = $this->commissionCalculator->calculate(...$operations);

        foreach ($operationsWithCommissions as $operationWithCommission) {
            echo $operationWithCommission->getCommission() . PHP_EOL;
        }
    }
}
