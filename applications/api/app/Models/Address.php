<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'patient_id',
        'zipcode',
        'address',
        'number',
        'complement',
        'neighborhood',
        'city',
        'state',
    ];
}
