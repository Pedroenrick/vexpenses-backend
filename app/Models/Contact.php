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

    public function feedback(): array
    {
        return [
            'name.required' => 'Name is required',
            'name.min' => 'Name must be at least 3 characters',
            'name.string' => 'Name must be a string',
            'photo.image' => 'Photo must be an image',
            'photo.mimes' => 'Photo must be a file of type: jpeg,png,jpg,gif,svg',
        ];
    }
}
