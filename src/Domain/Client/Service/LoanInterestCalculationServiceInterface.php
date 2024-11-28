<?php

namespace App\Domain\Client\Service;

use App\Domain\Client\Entity\Loan;

interface LoanInterestCalculationServiceInterface
{
    public function getInterest(Loan $loan): float;
}
