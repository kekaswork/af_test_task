<?php

namespace App\Domain\Client\Exception;

use InvalidArgumentException;

class InvalidFICOScoreException extends InvalidArgumentException implements DomainException
{
}
