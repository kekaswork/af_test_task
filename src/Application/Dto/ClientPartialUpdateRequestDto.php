<?php

namespace App\Application\Dto;

use App\Domain\Client\ValueObject\Address;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class ClientPartialUpdateRequestDto
{
    private ?string $id = null;

    #[SerializedName('first_name')]
    public ?string $firstName = null;

    #[SerializedName('last_name')]
    public ?string $lastName = null;

    #[Assert\Date]
    #[SerializedName('date_of_birth')]
    public ?string $dateOfBirth = null;

    #[Assert\Regex(pattern: '/^\d{3}-\d{2}-\d{4}$/', message: 'The SSN must be in the format XXX-XX-XXXX')]
    #[SerializedName('ssn')]
    public ?string $ssn = null;

    #[SerializedName('street')]
    public ?string $street = null;

    #[SerializedName('city')]
    public ?string $city = null;

    #[Assert\Choice(callback: [
        Address::class,
        'getUsValidStates'
    ], message: 'The state must be a valid US state abbreviation (e.g., CA, NY).')]
    #[SerializedName('state')]
    public ?string $state = null;

    #[Assert\Regex(pattern: '/^\d{5}(-\d{4})?$/', message: 'The zip code must be in the format 12345 or 12345-6789')]
    #[SerializedName('zip')]
    public ?string $zip = null;

    #[Assert\Email]
    #[SerializedName('email')]
    public ?string $email = null;

    #[Assert\GreaterThanOrEqual(0)]
    #[SerializedName('fico_score')]
    public ?int $ficoScore = null;

    #[Assert\Regex(pattern: '/^\+1\d{10}$/', message: 'The phone number must be in the format +11234567890')]
    #[SerializedName('phone')]
    public ?string $phone = null;

    #[Assert\GreaterThanOrEqual(0)]
    #[SerializedName('monthly_income')]
    public ?float $monthlyIncome = null;

    public function __construct()
    {
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): ClientPartialUpdateRequestDto
    {
        $this->id = $id;

        return $this;
    }

    public function isEmpty(): bool
    {
        return empty($this->firstName) &&
            empty($this->lastName) &&
            empty($this->dateOfBirth) &&
            empty($this->ssn) &&
            empty($this->street) &&
            empty($this->city) &&
            empty($this->state) &&
            empty($this->zip) &&
            empty($this->email) &&
            empty($this->ficoScore) &&
            empty($this->phone) &&
            empty($this->monthlyIncome);
    }
}
