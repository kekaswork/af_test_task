<?php

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Client\Entity\Loan;
use App\Domain\Client\Repository\LoanRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class LoanRepository implements LoanRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function save(Loan $loan): Loan
    {
        $this->entityManager->persist($loan);
        $this->entityManager->flush();

        return $loan;
    }
}
