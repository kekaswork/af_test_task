<?php

namespace App\Infrastructure\Service;

use App\Domain\Client\Entity\Client;
use App\Domain\Client\Service\NotificationServiceInterface;

class EmailNotificationService implements NotificationServiceInterface
{
    public function notify(Client $client, string $message): void
    {
        // TODO: sending email to client via external package (Mailer, Postmark etc)
    }
}
