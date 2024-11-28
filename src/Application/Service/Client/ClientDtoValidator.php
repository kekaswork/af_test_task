<?php

namespace App\Application\Service\Client;

use App\Application\Dto\ClientDto;
use App\Domain\Client\Entity\Client;
use App\Domain\Client\Exception\ClientAlreadyExistsException;
use App\Domain\Client\Exception\ClientNotFoundException;
use App\Domain\Client\Exception\InvalidAddressException;
use App\Domain\Client\Exception\InvalidFicoScoreException;
use App\Domain\Client\Repository\ClientRepositoryInterface;
use App\Domain\Client\ValueObject\Address;
use App\Domain\Client\ValueObject\ClientId;
use App\Domain\Client\ValueObject\FicoScore;
use DateTimeImmutable;
use InvalidArgumentException;

/**
 * Class ClientDtoValidator
 * Validates various properties of a client and their DTO representation.
 */
class ClientDtoValidator
{
    public function __construct(
        private ClientRepositoryInterface $clientRepository
    ) {
    }

    /**
     * Validates that a client exists by their ID.
     *
     * @param string $clientId
     * @throws ClientNotFoundException
     */
    public function validateClientId(string $clientId): void
    {
        if (! $this->clientRepository->findById(ClientId::fromString($clientId))) {
            throw new ClientNotFoundException('Client not found.');
        }
    }

    /**
     * Validates the uniqueness of a client's email.
     *
     * @param string $email
     * @param Client|null $existingClient Optional existing client to exclude from validation.
     * @throws ClientAlreadyExistsException
     */
    public function validateUniqueEmail(string $email, ?Client $existingClient = null): void
    {
        if ($existingClient && $existingClient->getEmail() === $email) {
            return;
        }
        if ($this->clientRepository->findByEmail($email)) {
            throw new ClientAlreadyExistsException("Client with email {$email} already exists.");
        }
    }

    /**
     * Validates the uniqueness of a client's SSN.
     *
     * @param string $ssn
     * @param Client|null $existingClient Optional existing client to exclude from validation.
     * @throws ClientAlreadyExistsException
     */
    public function validateUniqueSsn(string $ssn, ?Client $existingClient = null): void
    {
        if ($existingClient && $existingClient->getSsn() === $ssn) {
            return;
        }
        if ($this->clientRepository->findBySsn($ssn)) {
            throw new ClientAlreadyExistsException("Client with SSN {$ssn} already exists.");
        }
    }

    /**
     * Validates and creates an Address object from a ClientDto.
     *
     * @param ClientDto $clientDto
     * @return Address
     * @throws InvalidArgumentException If the address is invalid.
     */
    public function validateAddress(ClientDto $clientDto): Address
    {
        try {
            return new Address(
                street: $clientDto->street,
                city: $clientDto->city,
                state: $clientDto->state,
                zip: $clientDto->zip,
            );
        } catch (InvalidAddressException $e) {
            throw new InvalidArgumentException('Invalid address.');
        }
    }

    /**
     * Validates and creates a FICO score value object.
     *
     * @param int $ficoScore
     * @return FicoScore
     * @throws InvalidArgumentException If the FICO score is invalid.
     */
    public function validateFicoScore(int $ficoScore): FicoScore
    {
        try {
            return new FicoScore($ficoScore);
        } catch (InvalidFicoScoreException $e) {
            throw new InvalidArgumentException('Invalid FICO score.');
        }
    }

    /**
     * Validates and returns a DateTimeImmutable object for the client's date of birth.
     *
     * @param string $dateOfBirth
     * @return DateTimeImmutable
     * @throws InvalidArgumentException If the date of birth is invalid or in the future.
     */
    public function validateDateOfBirth(string $dateOfBirth): DateTimeImmutable
    {
        try {
            $dob = new DateTimeImmutable($dateOfBirth);
            if ($dob > new DateTimeImmutable()) {
                throw new InvalidArgumentException('Date of birth cannot be in the future.');
            }
            return $dob;
        } catch (\Exception $e) {
            throw new InvalidArgumentException('Invalid date of birth.');
        }
    }

    /**
     * Validates that a client exists and returns the Client entity.
     *
     * @param ClientId $clientId
     * @return Client
     * @throws ClientNotFoundException
     */
    public function validateClientExistence(ClientId $clientId): Client
    {
        $client = $this->clientRepository->findById($clientId);
        if (! $client) {
            throw new ClientNotFoundException('Client not found.');
        }

        return $client;
    }
}
