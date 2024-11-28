<?php

namespace App\Domain\Client\Service;

use App\Domain\Client\Entity\Client;

interface NotificationServiceInterface
{
    public function notify(Client $client, string $message): void;
}
