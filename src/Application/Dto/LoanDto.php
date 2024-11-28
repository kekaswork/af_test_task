<?php

namespace App\Application\Dto;

use App\Domain\Client\ValueObject\Address;
use DateTimeImmutable;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class LoanDto
{
    #[Groups(['loan_read'])]
    private ?string $id = null;

    #[Assert\NotBlank]
    #[SerializedName('name')]
    public string $name;

    #[Assert\NotBlank]
    #[Assert\GreaterThan(0)]
    #[SerializedName('term')]
    public int $term;

    #[Groups(['loan_read'])]
    #[Assert\GreaterThan(0)]
    #[SerializedName('interest')]
    public int $interest;

    #[Assert\NotBlank]
    #[Assert\GreaterThan(0)]
    #[SerializedName('sum')]
    public int $sum;

    #[Assert\NotBlank]
    #[SerializedName('client_id')]
    public string $clientId;

    #[Groups(['loan_read'])]
    public ?DateTimeImmutable $createdAt = null;

    #[Groups(['loan_read'])]
    public ?DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): LoanDto
    {
        $this->id = $id;

        return $this;
    }
}
