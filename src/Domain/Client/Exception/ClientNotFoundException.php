<?php

namespace App\Domain\Client\Exception;

use Exception;

class ClientNotFoundException extends Exception implements DomainException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}