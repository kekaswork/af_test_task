<?php

namespace App\Domain\Client\Service;

use App\Domain\Client\Entity\Client;

interface LoanEligibilityServiceInterface
{
    public function isEligible(Client $client): bool;
}
