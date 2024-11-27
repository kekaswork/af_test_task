<?php

namespace App\Domain\Client\ValueObject;

use Symfony\Component\Uid\Uuid;

abstract class AggregateRootId
{
    protected string $uuid;

    public function __construct(string $uuid)
    {
        $this->validate($uuid);
        $this->uuid = $uuid;
    }

    private function validate(string $uuid): void
    {
        if (! preg_match('/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i', $uuid)) {
            throw new \InvalidArgumentException('Not valid UUID');
        }
    }

    public static function generate(): static
    {
        return new static(Uuid::v4()->toRfc4122());
    }

    public function getValue(): string
    {
        return $this->uuid;
    }

    public function __toString(): string
    {
        return $this->uuid;
    }

    public function equals(self $other): bool
    {
        return $this->uuid === $other->getValue();
    }

    public static function fromString(string $id): static
    {
        return new static($id);
    }
}