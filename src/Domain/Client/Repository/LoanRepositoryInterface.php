<?php

namespace App\Domain\Client\Repository;

use App\Domain\Client\Entity\Loan;

interface LoanRepositoryInterface
{
    public function save(Loan $loan): Loan;
}
