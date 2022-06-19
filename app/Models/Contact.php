<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'photo',
    ];

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3',
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }
}
