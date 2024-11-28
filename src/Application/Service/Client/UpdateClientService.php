<?php

namespace App\Application\Service\Client;

use App\Application\Dto\ClientDto;
use App\Domain\Client\Exception\ClientAlreadyExistsException;
use App\Domain\Client\Exception\ClientNotFoundException;
use App\Domain\Client\Repository\ClientRepositoryInterface;
use App\Domain\Client\ValueObject\ClientId;

class UpdateClientService
{
    public function __construct(
        private ClientRepositoryInterface $clientRepository,
        private ClientDtoValidator $clientDtoValidator,
    ) {
    }

    /**
     * @throws ClientNotFoundException
     * @throws ClientAlreadyExistsException
     */
    public function execute(
        ClientDto $clientDto,
    ): string {
        // Validating ID
        $clientId = ClientId::fromString($clientDto->getId());

        $client = $this->clientDtoValidator->validateClientExistence($clientId);
        $this->clientDtoValidator->validateUniqueEmail($clientDto->email, $client);
        $this->clientDtoValidator->validateUniqueSsn($clientDto->ssn, $client);
        $address = $this->clientDtoValidator->validateAddress($clientDto);
        $ficoScore = $this->clientDtoValidator->validateFicoScore($clientDto->ficoScore);
        $dateOfBirth = $this->clientDtoValidator->validateDateOfBirth($clientDto->dateOfBirth);

        $client->setFirstName($clientDto->firstName)->setLastName($clientDto->lastName)->setDateOfBirth(
            $dateOfBirth
        )->setSsn($clientDto->ssn)->setAddress($address)->setFicoScore($ficoScore)->setEmail(
            $clientDto->email
        )->setPhone($clientDto->phone)->setMonthlyIncome($clientDto->monthlyIncome)->setupdatedAt(
            new \DateTimeImmutable('now')
        );

        // Save the client in the repository
        return $this->clientRepository->update($client)->getId()->getValue();
    }
}
