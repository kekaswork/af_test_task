<?php

namespace App\Application\Service\Client;

use App\Domain\Client\Entity\Client;

//use App\Domain\Client\Notification\EmailNotification;
//use App\Domain\Client\Notification\SmsNotification;

class NotifyClientService
{
//    private EmailNotification $emailNotification;
//    private SmsNotification $smsNotification;
//
//    public function __construct(EmailNotification $emailNotification, SmsNotification $smsNotification)
//    {
//        $this->emailNotification = $emailNotification;
//        $this->smsNotification = $smsNotification;
//    }
//
//    public function notify(Client $client, string $message): void
//    {
//        // Email notification.
//        $this->emailNotification->send($client->getEmail(), $message); // TODO
//
//        // SMS notification.
//        if ($client->getPhone()) {
//            $this->smsNotification->send($client->getPhone(), $message); // TODO
//        }
//    }
}
