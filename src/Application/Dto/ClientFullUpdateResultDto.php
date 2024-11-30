<?php

namespace App\Application\Dto;

use App\Domain\Client\Enum\ClientOperationType;

class ClientFullUpdateResultDto
{
    public function __construct(
        private string $id,
        private ClientOperationType $operation,
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getOperation(): ClientOperationType
    {
        return $this->operation;
    }
}
