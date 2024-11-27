<?php

namespace App\Domain\Client\Entity;

use App\Domain\Client\ValueObject\ClientId;
use App\Domain\Client\ValueObject\LoanId;
use DateTimeImmutable;

class Loan
{
    private function __construct(
        private LoanId $id,
        private ClientId $clientId,
        private string $name,
        private int $term,
        private float $interest,
        private float $sum,
        private DateTimeImmutable $createdAt,
        private ?DateTimeImmutable $updatedAt,
    ) {
    }

    public function getId(): LoanId
    {
        return $this->id;
    }

    public function setId(LoanId $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getClientId(): ClientId
    {
        return $this->clientId;
    }

    public function setClientId(ClientId $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getTerm(): int
    {
        return $this->term;
    }

    public function setTerm(int $term): self
    {
        $this->term = $term;

        return $this;
    }

    public function getInterest(): float
    {
        return $this->interest;
    }

    public function setInterest(float $interest): self
    {
        $this->interest = $interest;

        return $this;
    }

    public function getSum(): float
    {
        return $this->sum;
    }

    public function setSum(float $sum): self
    {
        $this->sum = $sum;

        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
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
}