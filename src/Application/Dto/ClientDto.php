<?php

namespace App\Application\Dto;

use App\Domain\Client\ValueObject\Address;
use DateTimeImmutable;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class ClientDto
{
    #[Groups(['client_read'])]
    private ?string $id = null;

    #[Assert\NotBlank]
    #[SerializedName('first_name')]
    public string $firstName;

    #[Assert\NotBlank]
    #[SerializedName('last_name')]
    public string $lastName;

    #[Assert\NotBlank]
    #[Assert\Date]
    #[SerializedName('date_of_birth')]
    public string $dateOfBirth;

    #[Assert\NotBlank]
    #[Assert\Regex(pattern: '/^\d{3}-\d{2}-\d{4}$/', message: 'The SSN must be in the format XXX-XX-XXXX')]
    #[SerializedName('ssn')]
    public string $ssn;

    #[Assert\NotBlank]
    #[SerializedName('street')]
    public string $street;

    #[Assert\NotBlank]
    #[SerializedName('city')]
    public string $city;

    #[Assert\NotBlank]
    #[Assert\Choice(callback: [
        Address::class,
        'getUsValidStates'
    ], message: 'The state must be a valid US state abbreviation (e.g., CA, NY).')]
    #[SerializedName('state')]
    public string $state;

    #[Assert\NotBlank]
    #[Assert\Regex(pattern: '/^\d{5}(-\d{4})?$/', message: 'The zip code must be in the format 12345 or 12345-6789')]
    #[SerializedName('zip')]
    public string $zip;

    #[Assert\NotBlank]
    #[Assert\Email]
    #[SerializedName('email')]
    public string $email;

    #[Assert\NotBlank]
    #[Assert\GreaterThanOrEqual(0)]
    #[SerializedName('fico_score')]
    public int $ficoScore;

    #[Assert\NotBlank]
    #[Assert\Regex(pattern: '/^\+1\d{10}$/', message: 'The phone number must be in the format +11234567890')]
    #[SerializedName('phone')]
    public string $phone;

    #[Assert\NotBlank]
    #[Assert\GreaterThanOrEqual(0)]
    #[SerializedName('monthly_income')]
    public float $monthlyIncome;

    #[Groups(['client_read'])]
    public ?DateTimeImmutable $createdAt = null;

    #[Groups(['client_read'])]
    public ?DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): ClientDto
    {
        $this->id = $id;

        return $this;
    }
}
