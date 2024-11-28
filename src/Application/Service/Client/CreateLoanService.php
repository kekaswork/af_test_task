<?php

namespace App\Application\Service\Client;

use App\Application\Dto\LoanDto;
use App\Domain\Client\Entity\Client;
use App\Domain\Client\Entity\Loan;
use App\Domain\Client\Exception\ClientNotFoundException;
use App\Domain\Client\Repository\ClientRepositoryInterface;
use App\Domain\Client\Repository\LoanRepositoryInterface;
use App\Domain\Client\ValueObject\ClientId;
use App\Domain\Client\ValueObject\LoanId;

class CreateLoanService
{
    public function __construct(
        private LoanRepositoryInterface $loanRepository,
        private ClientRepositoryInterface $clientRepository,
        private LoanEligibilityService $eligibilityService,
        private LoanInterestCalculationService $loanInterestCalculationService,
    ) {
    }

    public function execute(
        LoanDto $loanDto,
    ): array {
        $clientId = ClientId::fromString($loanDto->clientId);
        $client = $this->clientRepository->findById($clientId);
        if (! $client) {
            throw new ClientNotFoundException('Client not found.');
        }

        if (! $client->isEligibleForLoan($this->eligibilityService)) {
            return ['is_approved' => false];
        }
        $loanId = LoanId::generate();
        $interest = $client->getInterestRate($this->loanInterestCalculationService);

        $loan = Loan::create(
            loanId: $loanId,
            client: $client,
            name: $loanDto->name,
            term: $loanDto->term,
            interest: $interest,
            sum: $loanDto->sum,
        );
        $loan = $this->loanRepository->save($loan);

        return [
            'is_approved' => true,
            'interest' => $loan->getInterest(),
            'loan_id' => $loan->getId()->getValue(),
        ];
    }
}
