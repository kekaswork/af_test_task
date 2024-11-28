<?php

namespace App\Tests\Mock\Repository;

use App\Domain\Client\Entity\Client;
use App\Domain\Client\Repository\ClientRepositoryInterface;
use App\Domain\Client\ValueObject\ClientId;

final class MockClientRepository implements ClientRepositoryInterface
{
    private static array $clients = [];

    public function save(Client $client): Client
    {
        self::$clients[(string)$client->getId()] = $client;
        return $client;
    }

    public function update(Client $client): Client
    {
        $id = (string)$client->getId();
        if (isset(self::$clients[$id])) {
            self::$clients[$id] = $client;
        }

        return $client;
    }

    public function exists(ClientId $id): bool
    {
        return isset(self::$clients[(string)$id]);
    }

    public function findById(ClientId $id): ?Client
    {
        return self::$clients[(string)$id] ?? null;
    }

    public function findByEmail(string $email): ?Client
    {
        foreach (self::$clients as $client) {
            if ($client->getEmail() === $email) {
                return $client;
            }
        }

        return null;
    }

    public function findBySsn(string $ssn): ?Client
    {
        foreach (self::$clients as $client) {
            if ($client->getSsn() === $ssn) {
                return $client;
            }
        }

        return null;
    }

    public function findAll(): array
    {
        return array_values(self::$clients);
    }

    public static function clear(): void
    {
        self::$clients = [];
    }
}
