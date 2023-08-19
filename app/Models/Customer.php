<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    const MAS_GENDER = 'M';
    const FEM_GENDER = 'F';
    const OTH_GENDER = 'O';

    const AVAILABLE_GENDERS = [
        self::OTH_GENDER,
        self::FEM_GENDER,
        self::MAS_GENDER
    ];

    protected $fillable = [
        'first_name',
        'last_name',
        'document',
        'birthdate',
        'gender'
    ];

    public function address(): HasOne
    {
        return $this->hasOne(Address::class);
    }
}
