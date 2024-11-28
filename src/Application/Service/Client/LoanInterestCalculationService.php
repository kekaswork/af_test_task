<?php

namespace App\Application\Service\Client;

use App\Domain\Client\Entity\Loan;
use App\Domain\Client\Service\LoanInterestCalculationServiceInterface;

class LoanInterestCalculationService implements LoanInterestCalculationServiceInterface
{
    public const float BASE_RATE = 10.00; // Base interest rate was not provided in the technical task, took a random number.

    public function getInterest(Loan $loan): float
    {
        return self::BASE_RATE;
    }
}
