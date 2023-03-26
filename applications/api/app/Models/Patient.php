<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
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

    public function scopeSearch($query, $search)
    {
        if (!empty($search)) {
                $query->where('name', 'LIKE', "%$search%")
                        ->orWhere('cpf', 'LIKE', "%$search%");
        }

        return $query;
    }
}
