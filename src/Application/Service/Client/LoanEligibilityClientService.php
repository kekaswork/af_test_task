<?php

namespace App\Application\Service\Client;

use App\Domain\Client\Exception\ClientNotFoundException;
use App\Domain\Client\Service\LoanManagerService;
use App\Domain\Client\ValueObject\ClientId;
use App\Domain\Client\Repository\ClientRepositoryInterface;

/**
 * Service to check if a client is eligible for a loan.
 */
class LoanEligibilityClientService
{
    public function __construct(
        private ClientRepositoryInterface $clientRepository,
    ) {
    }

    /**
     * Checks if the client with the given UUID is eligible for a loan.
     *
     * @param string $uuid The unique identifier of the client.
     *
     * @return bool True if the client is eligible, otherwise false.
     *
     * @throws ClientNotFoundException If the client cannot be found by the provided UUID.
     */
    public function execute(
        string $uuid,
    ): bool {
        $clientId = ClientId::fromString($uuid);
        $client = $this->clientRepository->findById($clientId);
        if (! $client) {
            throw new ClientNotFoundException('Client not found.');
        }
        return (new LoanManagerService($client))->isEligibleForLoan();
    }
}
