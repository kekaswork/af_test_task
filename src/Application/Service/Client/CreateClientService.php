<?php

namespace App\Application\Service\Client;

use App\Application\Dto\ClientDto;
use App\Domain\Client\Entity\Client;
use App\Domain\Client\ValueObject\ClientId;
use App\Domain\Client\Repository\ClientRepositoryInterface;
use App\Domain\Client\Exception\ClientAlreadyExistsException;
use InvalidArgumentException;

/**
 * Service for creating a new client.
 */
class CreateClientService
{
    public function __construct(
        private ClientRepositoryInterface $clientRepository,
        private ClientDtoValidator $clientDtoValidator,
    ) {
    }

    /**
     * Executes the process of creating a new client.
     *
     * @param ClientDto $clientDto Data Transfer Object containing client information.
     *
     * @return string The ID of the newly created client.
     *
     * @throws ClientAlreadyExistsException If the email or SSN already exists.
     * @throws InvalidArgumentException If validation of any client property fails.
     */
    public function execute(
        ClientDto $clientDto,
    ): string {
        $this->clientDtoValidator->validateUniqueEmail($clientDto->email);
        $this->clientDtoValidator->validateUniqueSsn($clientDto->ssn);
        $address = $this->clientDtoValidator->validateAddress($clientDto);
        $ficoScore = $this->clientDtoValidator->validateFicoScore($clientDto->ficoScore);
        $dateOfBirth = $this->clientDtoValidator->validateDateOfBirth($clientDto->dateOfBirth);

        // Creating new client.
        $client = Client::create(
            id: ClientId::generate(),
            firstName: $clientDto->firstName,
            lastName: $clientDto->lastName,
            dateOfBirth: $dateOfBirth,
            ssn: $clientDto->ssn,
            address: $address,
            ficoScore: $ficoScore,
            email: $clientDto->email,
            phone: $clientDto->phone,
            monthlyIncome: $clientDto->monthlyIncome,
        );

        // Save the client in the repository
        return $this->clientRepository->save($client)->getId()->getValue();
    }
}
