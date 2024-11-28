<?php

namespace App\Domain\Client\Service;

use App\Domain\Client\Entity\Client;

final class LoanManagerService
{
    private const int MIN_FICO_SCORE = 500;
    private const int MIN_INCOME = 1000;
    private const int MIN_AGE = 18;
    private const int MAX_AGE = 60;
    private const array STATES_WHITELIST = ['CA', 'NY', 'NV'];
    private const float BASE_RATE = 10.00;

    public function __construct(
        private Client $client,
    ) {
    }

    public function isEligibleForLoan(): bool
    {
        // Checking fico score.
        if (! $this->client->getFicoScore()->isAboveThreshold(self::MIN_FICO_SCORE)) {
            return false;
        }

        // Checking client's age.
        if ($this->client->getAge() < self::MIN_AGE || $this->client->getAge() > self::MAX_AGE) {
            return false;
        }

        // Checking by monthly income.
        if ($this->client->getMonthlyIncome() < self::MIN_INCOME) {
            return false;
        }

        // Checking the client's state.
        $state = $this->client->getAddress()->getState();
        if (! in_array($state, self::STATES_WHITELIST)) {
            return false;
        }

        // Custom logic for clients from NY.
        if ($state === 'NY') {
            return rand(0, 1) === 1;
        }

        return true;
    }

    public function getInterestRate(): float
    {
        $interest = self::BASE_RATE;
        if ($this->client->getAddress()->getState() === 'CA') {
            $interest += 11.49;
        }

        return round($interest, 2);
    }
}
