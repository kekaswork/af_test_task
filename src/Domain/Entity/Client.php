<?php

namespace App\Domain\Entity;

use App\Domain\Service\CreditEligibilityService;

class Client
{
    public function __construct(
        private string $firstName,
        private string $lastName,
        private int $age,
        private string $ssn,
        private string $streetAddress,
        private string $city,
        private string $state,
        private string $zipCode,
        private int $ficoCreditScore,
        private string $email,
        private string $phoneNumber,
        private float $monthlyIncome,
    ) {
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function setAge(int $age): void
    {
        $this->age = $age;
    }

    public function getSsn(): string
    {
        return $this->ssn;
    }

    public function setSsn(string $ssn): void
    {
        $this->ssn = $ssn;
    }

    public function getStreetAddress(): string
    {
        return $this->streetAddress;
    }

    public function setStreetAddress(string $streetAddress): void
    {
        $this->streetAddress = $streetAddress;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function setState(string $state): void
    {
        $this->state = $state;
    }

    public function getZipCode(): string
    {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode): void
    {
        $this->zipCode = $zipCode;
    }

    public function getFicoCreditScore(): int
    {
        return $this->ficoCreditScore;
    }

    public function setFicoCreditScore(int $ficoCreditScore): void
    {
        $this->ficoCreditScore = $ficoCreditScore;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function getMonthlyIncome(): float
    {
        return $this->monthlyIncome;
    }

    public function setMonthlyIncome(float $monthlyIncome): void
    {
        $this->monthlyIncome = $monthlyIncome;
    }

    public function isEligibleForCredit(): bool
    {
        return (new CreditEligibilityService())->isEligibleForCredit($this);
    }
}