<?php

namespace App\Domain\Client\Exception;

use InvalidArgumentException;

class InvalidFicoScoreException extends InvalidArgumentException implements DomainException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
