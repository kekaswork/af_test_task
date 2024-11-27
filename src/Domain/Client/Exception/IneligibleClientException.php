<?php

namespace App\Domain\Client\Exception;

use Exception;

class IneligibleClientException extends Exception implements DomainException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
