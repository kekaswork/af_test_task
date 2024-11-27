<?php

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Client\Entity\Client;
use App\Domain\Client\Repository\ClientRepositoryInterface;
use App\Domain\Client\ValueObject\ClientId;
use Doctrine\ORM\EntityManagerInterface;

final readonly class ClientRepository implements ClientRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function save(Client $client): Client
    {
        $this->entityManager->persist($client);
        $this->entityManager->flush();

        return $client;
    }

    public function update(Client $client): Client
    {
        $this->entityManager->flush();

        return $client;
    }

    public function exists(ClientId $id): bool
    {
        return $this->findById($id) !== null;
    }

    public function findById(ClientId $id): ?Client
    {

        return $this->entityManager->getRepository(Client::class)->find($id);
    }

    public function findByEmail(string $email): ?Client
    {
        return $this->entityManager->getRepository(Client::class)->findOneBy(['email' => $email]);
    }

    public function findBySsn(string $ssn): ?Client
    {
        return $this->entityManager->getRepository(Client::class)->findOneBy(['ssn' => $ssn]);
    }

    public function findAll(): array
    {
        return [];
        // TODO: Implement findAll() method.
    }
}