<?php

namespace App\Domain\Client\Service;

use App\Domain\Client\Entity\Client;

interface LoanInterestCalculationServiceInterface
{
    public function getInterest(Client $client): float;
}
