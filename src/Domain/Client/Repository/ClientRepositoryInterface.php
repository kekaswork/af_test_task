<?php

namespace App\Domain\Client\Repository;

use App\Domain\Client\Entity\Client;
use App\Domain\Client\ValueObject\ClientId;

interface ClientRepositoryInterface
{
    /**
     * @param Client $client
     */
    public function save(Client $client): Client;

    /**
     * @param Client $client
     *
     * @return Client
     */
    public function update(Client $client): Client;

    /**
     * @param ClientId $id
     *
     * @return bool
     */
    public function exists(ClientId $id): bool;

    /**
     * @param ClientId $id
     *
     * @return Client|null
     */
    public function findById(ClientId $id): ?Client;

    /**
     * @param string $email
     * @return Client|null
     */
    public function findByEmail(string $email): ?Client;

    /**
     * @param string $ssn
     * @return Client|null
     */
    public function findBySsn(string $ssn): ?Client;

    /**
     * @return Client[]
     */
    public function findAll(): array;
}