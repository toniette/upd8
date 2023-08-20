<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    const DEFAULT_COUNTRY = 'BR';

    protected $attributes = [
        'country' => self::DEFAULT_COUNTRY,
    ];

    protected $fillable = [
        'street',
        'number',
        'complement',
        'district',
        'city',
        'state',
        'country',
        'zip_code',
    ];

    public function country(): Attribute
    {
        return new Attribute(
            get: function ($value) {
                return strtoupper($value);
            },
            set: function ($value) {
                if (is_null($value)) {
                    return self::DEFAULT_COUNTRY;
                }
                return strtoupper($value);
            }
        );
    }

    public function zipCode(): Attribute
    {
        return new Attribute(
            get: function ($value) {
                return $value;
            },
            set: function ($value) {
                return preg_replace('/\D/', '', $value);
            }
        );
    }
}
