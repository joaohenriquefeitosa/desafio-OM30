<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pacient extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'picture',
        'name',
        'mother_name',
        'birth_date',
        'cpf',
        'cns',
    ];
}
