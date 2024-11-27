<?php

namespace App\Application\Service\Client;

use App\Domain\Client\Entity\Client;
use App\Domain\Client\Service\LoanEligibilityServiceInterface;

class LoanEligibilityService implements LoanEligibilityServiceInterface
{
    private const int MIN_FICO_SCORE = 500;
    private const int MIN_INCOME = 1000;
    private const int MIN_AGE = 18;
    private const int MAX_AGE = 60;
    private const array STATES_WHITELIST = ['CA', 'NY', 'NV'];

    public function isEligible(Client $client): bool
    {
        // Checking fico score.
        if (! $client->getFicoScore()->isAboveThreshold(self::MIN_FICO_SCORE)) {
            return false;
        }

        // Checking client's age.
        if ($client->getAge() < self::MIN_AGE || $client->getAge() > self::MAX_AGE) {
            return false;
        }

        // Checking by monthly income.
        if ($client->getMonthlyIncome() < self::MIN_INCOME) {
            return false;
        }

        // Checking the client's state.
        $state = $client->getAddress()->getState();
        if (! in_array($state, self::STATES_WHITELIST)) {
            return false;
        }

        // Custom logic for clients from NY.
        if ($state === 'NY') {
            return $this->randomDecision();
        }

        return true;
    }

    public function adjustInterestRate(Client $client, float $baseRate): float
    {
        // Update the interest rate for clients from CA.
        if ($client->getAddress()->getState() === 'CA') {
            return $baseRate + 11.49;
        }

        return $baseRate;
    }

    private function randomDecision(): bool
    {
        return rand(0, 1) === 1;
    }
}
