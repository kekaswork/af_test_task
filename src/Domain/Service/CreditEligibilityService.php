<?php

namespace App\Domain\Service;

use App\Domain\Entity\Client;

class CreditEligibilityService
{
    public function isEligibleForCredit(Client $client): bool
    {
        if ($client->getFicoCreditScore() < 500) {
            return false;
        }
        if ($client->getMonthlyIncome() < 1000) {
            return false;
        }
        if ($client->getAge() < 18 || $client->getAge() > 60) {
            return false;
        }
        if (!in_array($client->getState(), ['CA', 'NY', 'NV'])) {
            return false;
        }

        return true;
    }
}