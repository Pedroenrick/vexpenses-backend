<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    use HasFactory;

    protected $fillable = [
        'contact_id',
        'number',
        'ddd'
    ];

    public function rules(): array
    {
        return [
            'contact_id' => 'exists:contacts,id',
            'number' => 'required|string|max:20',
            'ddd' => 'required|string|max:2'
        ];
    }

    public function contact(){
        return $this->belongsTo(Contact::class);
    }
}
