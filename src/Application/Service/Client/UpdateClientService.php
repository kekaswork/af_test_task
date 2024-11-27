<?php

namespace App\Application\Service\Client;

use App\Application\Dto\ClientDto;
use App\Domain\Client\Entity\Client;
use App\Domain\Client\Exception\ClientAlreadyExistsException;
use App\Domain\Client\Exception\ClientNotFoundException;
use App\Domain\Client\Repository\ClientRepositoryInterface;
use App\Domain\Client\ValueObject\Address;
use App\Domain\Client\ValueObject\ClientId;
use App\Domain\Client\ValueObject\FicoScore;

readonly class UpdateClientService
{
    public function __construct(
        private ClientRepositoryInterface $clientRepository,
    )
    {}

    /**
     * @throws ClientNotFoundException
     * @throws ClientAlreadyExistsException
     */
    public function execute(
        ClientDto $clientDto,
    ): string
    {
        // Validating ID
        $clientId = ClientId::fromString($clientDto->getId());
        $client = $this->clientRepository->findById($clientId);
        if (!$client) {
            throw new ClientNotFoundException('Client not found.');
        }

        // Validating Email
        if ($client->getEmail() !== $clientDto->email) {
            if ($this->clientRepository->findByEmail($clientDto->email)) {
                throw new ClientAlreadyExistsException("Client with email {$clientDto->email} already exists.");
            }
        }

        // Validating SSN
        if ($client->getSsn() !== $clientDto->ssn) {
            if ($this->clientRepository->findBySsn($clientDto->ssn)) {
                throw new ClientAlreadyExistsException("Client with SSN {$clientDto->ssn} already exists.");
            }
        }

        $address = new Address(
            street: $clientDto->street,
            city: $clientDto->city,
            state: $clientDto->state,
            zip: $clientDto->zip
        );
        $ficoScore = new FicoScore($clientDto->ficoScore);

        $client
            ->setFirstName($clientDto->firstName)
            ->setLastName($clientDto->lastName)
            ->setAge($clientDto->age)
            ->setSsn($clientDto->ssn)
            ->setAddress($address)
            ->setFicoScore($ficoScore)
            ->setEmail($clientDto->email)
            ->setPhone($clientDto->phone)
            ->setMonthlyIncome($clientDto->monthlyIncome)
            ->setupdatedAt(new \DateTimeImmutable('now'));

        // Save the client in the repository
        return $this->clientRepository->update($client)->getId()->getValue();
    }
}