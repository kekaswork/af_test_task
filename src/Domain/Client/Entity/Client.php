<?php

namespace App\Domain\Client\Entity;

use App\Domain\Client\ValueObject\Address;
use App\Domain\Client\ValueObject\ClientId;
use App\Domain\Client\ValueObject\FicoScore;
use DateTimeImmutable;

class Client
{
    private function __construct(
        private ClientId $id,
        private string $firstName,
        private string $lastName,
        private int $age,
        private string $ssn,
        private Address $address,
        private FicoScore $ficoScore,
        private float $monthlyIncome,
        private string $email,
        private string $phone,
        private DateTimeImmutable $createdAt,
        private ?DateTimeImmutable $updatedAt,
    ) {
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = null;
    }

    public function getId(): ClientId
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getSsn(): string
    {
        return $this->ssn;
    }

    public function getAddress(): Address
    {
        return $this->address;
    }

    public function getFicoScore(): FicoScore
    {
        return $this->ficoScore;
    }

    public function getMonthlyIncome(): float
    {
        return $this->monthlyIncome;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public static function create(
        ClientId $id,
        string $firstName,
        string $lastName,
        int $age,
        string $ssn,
        Address $address,
        FicoScore $ficoScore,
        string $email,
        string $phone,
        float $monthlyIncome,
        ?DateTimeImmutable $createdAt = new \DateTimeImmutable('now'),
        ?DateTimeImmutable $updatedAt = null,
    ): self
    {
        return new self(
            id: $id,
            firstName: $firstName,
            lastName: $lastName,
            age: $age,
            ssn: $ssn,
            address: $address,
            ficoScore: $ficoScore,
            monthlyIncome: $monthlyIncome,
            email: $email,
            phone: $phone,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
        );
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function setId(ClientId $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function setSsn(string $ssn): self
    {
        $this->ssn = $ssn;

        return $this;
    }

    public function setAddress(Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function setFicoScore(FicoScore $ficoScore): self
    {
        $this->ficoScore = $ficoScore;

        return $this;
    }

    public function setMonthlyIncome(float $monthlyIncome): self
    {
        $this->monthlyIncome = $monthlyIncome;

        return $this;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }


}