<?php

namespace App\Domain\Client\Exception;

use InvalidArgumentException;

class InvalidAddressException extends InvalidArgumentException implements DomainException
{
}