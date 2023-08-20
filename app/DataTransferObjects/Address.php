<?php

namespace App\DataTransferObjects;

use Spatie\LaravelData\Data;

class Address extends Data
{
    public function __construct(
        public string $street,
        public string $number,
        public ?string $complement,
        public string $district,
        public string $city,
        public string $state,
        public string $zip_code,
        public ?string $country = null,
    ) {}
}
