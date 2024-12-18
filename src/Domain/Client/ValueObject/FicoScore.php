<?php

namespace App\Domain\Client\ValueObject;

use App\Domain\Client\Exception\InvalidFicoScoreException;

class FicoScore
{
    private int $value;

    public function __construct(int $score)
    {
        $this->validate($score);
        $this->value = $score;
    }

    private function validate(int $score): void
    {
        if ($score < 0) {
            throw new InvalidFicoScoreException(
                "FICO score must be positive number. Provided: $score"
            );
        }
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function isAboveThreshold(int $threshold): bool
    {
        return $this->value > $threshold;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->getValue();
    }
}
