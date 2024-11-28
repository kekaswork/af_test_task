<?php

namespace App\Application\Service\Client;

use App\Domain\Client\Entity\Client;
use App\Domain\Client\Service\LoanInterestCalculationServiceInterface;

class LoanInterestCalculationService implements LoanInterestCalculationServiceInterface
{
    public const float BASE_RATE = 10.00; // Base interest rate was not provided in the technical task, took a random number.

    public function getInterest(Client $client): float
    {
        $interest = self::BASE_RATE;
        if ($client->getAddress()->getState() === 'CA') {
            $interest += 11.49;
        }

        return round($interest, 2);
    }
}
