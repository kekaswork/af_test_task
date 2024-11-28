<?php

namespace App\Application\Service\Client;

use App\Application\Dto\ClientDto;
use App\Domain\Client\Entity\Client;
use App\Domain\Client\Exception\ClientNotFoundException;
use App\Domain\Client\Exception\InvalidAddressException;
use App\Domain\Client\Exception\InvalidFicoScoreException;
use App\Domain\Client\ValueObject\ClientId;
use App\Domain\Client\ValueObject\FicoScore;
use App\Domain\Client\ValueObject\Address;
use App\Domain\Client\Repository\ClientRepositoryInterface;
use App\Domain\Client\Exception\ClientAlreadyExistsException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use InvalidArgumentException;
use mysql_xdevapi\Exception;

class LoanEligibilityClientService
{
    public function __construct(
        private ClientRepositoryInterface $clientRepository,
        private LoanEligibilityService $eligibilityService,
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

        return $client->isEligibleForLoan($this->eligibilityService);
    }
}
