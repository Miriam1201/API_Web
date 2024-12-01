<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'username',
        'email',
        'profile_image',
        'date',
        'game',
        'content',
        'images',
        'video_url',
        'tags',
        'likes',
        'comments',
        'shares'
    ];

    protected $casts = [
        'images' => 'array',
        'tags' => 'array',
    ];
}
