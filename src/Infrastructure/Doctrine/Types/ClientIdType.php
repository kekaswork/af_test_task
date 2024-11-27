<?php

namespace App\Infrastructure\Doctrine\Types;

use App\Domain\Client\ValueObject\ClientId;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class ClientIdType extends Type
{
    const string CLIENTID = 'clientid';

    public function convertToPHPValue($value, AbstractPlatform $platform): ClientId
    {
        return new ClientId($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof ClientId) {
            return $value->getValue();
        }

        return null;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return "VARCHAR(255)";
    }

    public function getName(): string
    {
        return self::CLIENTID;
    }
}