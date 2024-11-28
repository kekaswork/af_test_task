<?php

namespace App\Application\Service\Client;

use App\Domain\Client\Exception\ClientNotFoundException;
use App\Domain\Client\Service\LoanManagerService;
use App\Domain\Client\ValueObject\ClientId;
use App\Domain\Client\Repository\ClientRepositoryInterface;

class LoanEligibilityClientService
{
    public function __construct(
        private ClientRepositoryInterface $clientRepository,
    ) {
    }

    public function execute(
        string $uuid,
    ): bool {
        $clientId = ClientId::fromString($uuid);
        $client = $this->clientRepository->findById($clientId);
        if (!$client) {
            throw new ClientNotFoundException('Client not found.');
        }
        return (new LoanManagerService($client))->isEligibleForLoan();
    }
}
