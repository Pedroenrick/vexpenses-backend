<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'contact_id',
        'zipcode',
        'street',
        'number',
        'complement',
        'neighborhood',
        'city',
        'state'
    ];

    public function rules(): array
    {
        return [
            'contact_id' => 'exists:contacts,id',
            'zipcode' => 'required|string|max:8',
            'street' => 'required|string|max:100',
            'number' => 'required|integer|max:10',
            'complement' => 'nullable|string|max:100',
            'neighborhood' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:2'
        ];
    }
}
