<?php

namespace App\Models;

use App\Models\Question;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Form extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'limit_one_response',
        'creator_id',
        'allowed_domains',
    ];

    protected $casts = [
        'allowed_domains' => 'array',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
