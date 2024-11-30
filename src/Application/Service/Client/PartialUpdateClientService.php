<?php

namespace App\Application\Service\Client;

use App\Application\Dto\ClientFullUpdateResultDto;
use App\Application\Dto\ClientPartialUpdateRequestDto;
use App\Domain\Client\Enum\ClientOperationType;
use App\Domain\Client\Exception\ClientAlreadyExistsException;
use App\Domain\Client\Exception\ClientNotFoundException;
use App\Domain\Client\Repository\ClientRepositoryInterface;
use App\Domain\Client\ValueObject\ClientId;
use InvalidArgumentException;

class PartialUpdateClientService
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
        ClientPartialUpdateRequestDto $clientDto,
    ): ClientFullUpdateResultDto {
        if ($clientDto->isEmpty()) {
            throw new InvalidArgumentException('No valid client data is provided.');
        }

        // Validating ID
        $clientId = ClientId::fromString($clientDto->getId());
        $client = $this->clientDtoValidator->validateClientExistence($clientId);

        $hasChanges = false;
        if (! is_null($clientDto->firstName) && $clientDto->firstName !== $client->getFirstName()) {
            $hasChanges = true;
            $client->setFirstName($clientDto->firstName);
        }
        if (! is_null($clientDto->lastName) && $clientDto->lastName !== $client->getLastName()) {
            $hasChanges = true;
            $client->setLastName($clientDto->lastName);
        }
        if (! is_null($clientDto->dateOfBirth)) {
            $dateOfBirth = $this->clientDtoValidator->validateDateOfBirth($clientDto->dateOfBirth);
            if ($dateOfBirth !== $client->getDateOfBirth()) {
                $hasChanges = true;
                $client->setDateOfBirth($dateOfBirth);
            }
        }
        if (! is_null($clientDto->ficoScore) && $clientDto->ficoScore !== $client->getFicoScore()) {
            $ficoScore = $this->clientDtoValidator->validateFicoScore($clientDto->ficoScore);
            if (!$ficoScore->equals($client->getFicoScore())) {
                $hasChanges = true;
                $client->setFicoScore($ficoScore);
            }
        }
        if (
            ! is_null($clientDto->street) ||
            ! is_null($clientDto->city) ||
            ! is_null($clientDto->state) ||
            ! is_null($clientDto->zip)
        ) {
            $address = $this->clientDtoValidator->validateAddress(
                street: $clientDto->street,
                city: $clientDto->city,
                state: $clientDto->state,
                zip: $clientDto->zip,
            );
            if (!$address->equals($client->getAddress())) {
                $hasChanges = true;
                $client->setAddress($address);
            }
        }
        if (! is_null($clientDto->email) && $clientDto->email !== $client->getEmail()) {
            $hasChanges = true;
            $this->clientDtoValidator->validateUniqueEmail($clientDto->email, $client);
            $client->setEmail($clientDto->email);
        }
        if (! is_null($clientDto->ssn) && $clientDto->ssn !== $client->getSsn()) {
            $hasChanges = true;
            $this->clientDtoValidator->validateUniqueSsn($clientDto->ssn, $client);
            $client->setSsn($clientDto->ssn);
        }
        if (! is_null($clientDto->phone) && $clientDto->phone !== $client->getPhone()) {
            $hasChanges = true;
            $client->setPhone($clientDto->phone);
        }
        if (! is_null($clientDto->monthlyIncome) && $clientDto->monthlyIncome !== $client->getMonthlyIncome()) {
            $hasChanges = true;
            $client->setMonthlyIncome($clientDto->monthlyIncome);
        }
        // No need to perform any DB manipulations as no changes are required.
        if (!$hasChanges) {
            return new ClientFullUpdateResultDto(
                $clientId->getValue(),
                ClientOperationType::NOT_MODIFIED,
            );
        }

        return new ClientFullUpdateResultDto(
            $this->clientRepository->update($client)->getId()->getValue(),
            ClientOperationType::UPDATED,
        );
    }
}
