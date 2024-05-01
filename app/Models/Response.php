<?php

namespace App\Models;

use App\Models\Form;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Response extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id', 'value', 'form_id', 'user_id'
    ];
}
