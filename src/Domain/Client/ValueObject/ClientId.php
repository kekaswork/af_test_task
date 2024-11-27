<?php

namespace App\Domain\Client\ValueObject;

use InvalidArgumentException;
use Symfony\Component\Uid\Uuid;

class ClientId
{
    private string $value;

    public function __construct(string $value)
    {
        $this->validate($value);
        $this->value = $value;
    }

    private function validate(string $value): void
    {
        if (!$value) {
            throw new InvalidArgumentException("Invalid client ID");
        }
    }

    public static function generate(): self
    {
        return new self(Uuid::v4()->toRfc4122());
    }

    public static function fromString(string $id): self
    {
        return new self($id);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function equals(ClientId $other): bool
    {
        return $this->value === $other->getValue();
    }
}