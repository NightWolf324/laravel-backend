<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'allowed_domains', 'description', 'limit_one_response', 'creator_id'
    ];

    protected $casts = [
        'allowed_domains' => 'array',
    ];

    protected $hidden = [
        'allowed_domains', 'created_at', 'updated_at'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
}
