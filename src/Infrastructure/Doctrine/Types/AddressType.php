<?php

namespace App\Infrastructure\Doctrine\Types;

use App\Domain\Client\ValueObject\Address;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class AddressType extends Type
{
    public const string NAME = 'address';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'JSON';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?Address
    {
        if ($value === null) {
            return null;
        }

        $data = json_decode($value, true);

        return new Address(
            $data['street'] ?? '',
            $data['city'] ?? '',
            $data['state'] ?? '',
            $data['zip'] ?? ''
        );
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        if ($value === null) {
            throw new \InvalidArgumentException('Expected non-empty string.');
        }

        if (! $value instanceof Address) {
            throw new \InvalidArgumentException('Expected Address object.');
        }

        return json_encode([
            'street' => $value->getStreet(),
            'city' => $value->getCity(),
            'state' => $value->getState(),
            'zip' => $value->getZip(),
        ]);
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): true
    {
        return true;
    }
}
