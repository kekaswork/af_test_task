<?php

namespace App\Domain\Entity;

class Credit
{
    private string $name;
    private int $termMonths;
    private float $baseInterestRate;
    private float $amount;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getTermMonths(): int
    {
        return $this->termMonths;
    }

    public function setTermMonths(int $termMonths): void
    {
        $this->termMonths = $termMonths;
    }

    public function getBaseInterestRate(): float
    {
        return $this->baseInterestRate;
    }

    public function setBaseInterestRate(float $baseInterestRate): void
    {
        $this->baseInterestRate = $baseInterestRate;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    public function calculateInterestRate(Client $client): float
    {
        $rate = $this->baseInterestRate;
        if ($client->getState() === 'CA') {
            $rate += 11.49;
        }

        if ($client->getState() === 'NY') {
            return $this->customLogicForNy();
        }

        return $rate;
    }

    private function customLogicForNy(): bool
    {
        return rand(0, 1) === 1;
    }
}