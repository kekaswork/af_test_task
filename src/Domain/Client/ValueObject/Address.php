<?php

namespace App\Domain\Client\ValueObject;

use App\Domain\Client\Exception\InvalidAddressException;

class Address
{
    private string $street;
    private string $city;
    private string $state;
    private string $zip;

    public function __construct(string $street, string $city, string $state, string $zip)
    {
        $this->validateState($state);
        $this->validateZip($zip);

        $this->street = $street;
        $this->city = $city;
        $this->state = $state;
        $this->zip = $zip;
    }

    private function validateState(string $state): void
    {
        if (! in_array($state, self::getUsValidStates())) {
            throw new InvalidAddressException("Invalid state provided: $state");
        }
    }

    private function validateZip(string $zip): void
    {
        // Регулярное выражение для проверки ZIP-кода (5 или 9 цифр)
        if (! preg_match('/^\d{5}(-\d{4})?$/', $zip)) {
            throw new InvalidAddressException("Invalid ZIP code provided: $zip");
        }
    }

    public static function getUsValidStates(): array
    {
        return [
            'AL',
            'AK',
            'AZ',
            'AR',
            'CA',
            'CO',
            'CT',
            'DE',
            'FL',
            'GA',
            'HI',
            'ID',
            'IL',
            'IN',
            'IA',
            'KS',
            'KY',
            'LA',
            'ME',
            'MD',
            'MA',
            'MI',
            'MN',
            'MS',
            'MO',
            'MT',
            'NE',
            'NV',
            'NH',
            'NJ',
            'NM',
            'NY',
            'NC',
            'ND',
            'OH',
            'OK',
            'OR',
            'PA',
            'RI',
            'SC',
            'SD',
            'TN',
            'TX',
            'UT',
            'VT',
            'VA',
            'WA',
            'WV',
            'WI',
            'WY'
        ];
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function getZip(): string
    {
        return $this->zip;
    }
}