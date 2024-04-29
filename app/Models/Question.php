<?php

namespace App\Models;

use App\Models\Form;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'choice_type', 'choices', 'is_required', 'form_id'
    ];

    protected $casts = [
        'choices' => 'array',
        'is_required' => 'boolean'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];
}
