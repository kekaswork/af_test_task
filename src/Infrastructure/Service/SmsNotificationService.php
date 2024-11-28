<?php

namespace App\Infrastructure\Service;

use App\Domain\Client\Entity\Client;
use App\Domain\Client\Service\NotificationServiceInterface;

class SmsNotificationService implements NotificationServiceInterface
{
    public function notify(Client $client, string $message): void
    {
        // TODO: sending SMS to client via external package.
    }
}
