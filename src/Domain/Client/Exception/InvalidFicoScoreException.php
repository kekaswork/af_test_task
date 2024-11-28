<?php

namespace App\Domain\Client\Exception;

use InvalidArgumentException;

class InvalidFicoScoreException extends InvalidArgumentException implements DomainException
{
}
