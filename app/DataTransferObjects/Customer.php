<?php

namespace App\DataTransferObjects;

use Spatie\LaravelData\Data;

class Customer extends Data
{
    public function __construct(
        public string $first_name,
        public string $last_name,
        public string $document,
        public string $birthdate,
        public string $gender,
        public Address $address
    ) {}
}
