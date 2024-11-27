<?php

namespace App\Infrastructure\Doctrine\Types;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

abstract class AggregateRootType extends Type
{
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return "VARCHAR(255)";
    }

    public function getName(): string
    {
        return static::NAME;
    }
}