<?php

namespace App\Infrastructure\Doctrine\Types;

use App\Domain\Client\ValueObject\LoanId;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class LoanIdType extends AggregateRootType
{
    const string NAME = 'loanid';

    public function convertToPHPValue($uuid, AbstractPlatform $platform): LoanId
    {
        return new LoanId($uuid);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value instanceof LoanId) {
            return $value->getValue();
        }

        return null;
    }
}