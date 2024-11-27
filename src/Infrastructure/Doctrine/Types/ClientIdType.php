<?php

namespace App\Infrastructure\Doctrine\Types;

use App\Domain\Client\ValueObject\ClientId;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class ClientIdType extends AggregateRootType
{
    const string NAME = 'clientid';

    public function convertToPHPValue($uuid, AbstractPlatform $platform): ClientId
    {
        return new ClientId($uuid);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value instanceof ClientId) {
            return $value->getValue();
        }

        return null;
    }
}