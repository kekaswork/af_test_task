<?php

namespace App\Application\Service\Client;

use App\Application\Dto\ClientDto;
use App\Application\Dto\ClientFullUpdateResultDto;
use App\Domain\Client\Entity\Client;
use App\Domain\Client\Enum\ClientOperationType;
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
    ): ClientFullUpdateResultDto {
        // Validating ID
        $clientId = ClientId::fromString($clientDto->getId());
        // Trying to find client by the provided ID
        $client = $this->clientRepository->findById($clientId);
        // Validating some request data.
        $this->clientDtoValidator->validateUniqueEmail($clientDto->email, $client);
        $this->clientDtoValidator->validateUniqueSsn($clientDto->ssn, $client);
        $address = $this->clientDtoValidator->validateAddress($clientDto);
        $ficoScore = $this->clientDtoValidator->validateFicoScore($clientDto->ficoScore);
        $dateOfBirth = $this->clientDtoValidator->validateDateOfBirth($clientDto->dateOfBirth);

        if ($client instanceof Client) {
            // If there is a client with such ID, we should update it.
            $client
                ->setFirstName($clientDto->firstName)
                ->setLastName($clientDto->lastName)
                ->setDateOfBirth($dateOfBirth)
                ->setSsn($clientDto->ssn)
                ->setAddress($address)
                ->setFicoScore($ficoScore)
                ->setEmail($clientDto->email)
                ->setPhone($clientDto->phone)
                ->setMonthlyIncome($clientDto->monthlyIncome)
                ->setupdatedAt(new \DateTimeImmutable('now'));
            return new ClientFullUpdateResultDto($this->clientRepository->update($client)->getId()->getValue(), ClientOperationType::UPDATED);
        }

        // If the passed ID value is not associated with any records from the clients table,
        // we should create a new record with such ID.
        $client = Client::create(
            id: $clientId,
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
        return new ClientFullUpdateResultDto($this->clientRepository->save($client)->getId()->getValue(), ClientOperationType::CREATED);
    }
}
