<?php

namespace App\Infrastructure\Doctrine\Types;

use App\Domain\Client\ValueObject\FicoScore;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class FicoScoreType extends Type
{
    public const string FICOSCORE = 'ficoscore';

    public function convertToPHPValue($value, AbstractPlatform $platform): FicoScore
    {
        return new FicoScore((int)$value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): int
    {
        if ($value instanceof FicoScore) {
            return $value->getValue();
        }

        throw new \InvalidArgumentException('Expected non-empty value.');
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return "INTEGER";
    }

    public function getName(): string
    {
        return self::FICOSCORE;
    }
}
