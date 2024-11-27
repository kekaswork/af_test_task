<?php

namespace App\Application\Service\Client;

use App\Application\Dto\ClientDto;
use App\Domain\Client\Entity\Client;
use App\Domain\Client\ValueObject\ClientId;
use App\Domain\Client\ValueObject\FicoScore;
use App\Domain\Client\ValueObject\Address;
use App\Domain\Client\Repository\ClientRepositoryInterface;
use App\Domain\Client\Exception\ClientAlreadyExistsException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

readonly class CreateClientService
{
    public function __construct(
        private ClientRepositoryInterface $clientRepository,
    ) {
    }

    /**
     * @throws ClientAlreadyExistsException
     */
    public function execute(
        ClientDto $clientDto,
    ): string {

        // Check if the email or SSN already exists.
        if ($this->clientRepository->findByEmail($clientDto->email) !== null) {
            throw new ClientAlreadyExistsException("Client with email {$clientDto->email} already exists.");
        }
        if ($this->clientRepository->findBySsn($clientDto->ssn) !== null) {
            throw new ClientAlreadyExistsException("Client with SSN {$clientDto->ssn} already exists.");
        }

        $address = new Address(
            street: $clientDto->street,
            city: $clientDto->city,
            state: $clientDto->state,
            zip: $clientDto->zip
        );
        $ficoScore = new FicoScore($clientDto->ficoScore);

        // Creating new client.
        $client = Client::create(
            id: ClientId::generate(),
            firstName: $clientDto->firstName,
            lastName: $clientDto->lastName,
            dateOfBirth: new \DateTimeImmutable($clientDto->dateOfBirth),
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