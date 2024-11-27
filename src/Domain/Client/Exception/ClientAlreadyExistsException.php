<?php

namespace App\Domain\Client\Exception;

use Exception;

class ClientAlreadyExistsException extends Exception implements DomainException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}