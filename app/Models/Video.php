<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;
    // id, title, description, video_code, duration, created_at, updated_at

    protected $fillable = [
        'title',
        'description',
        'video_code',
        'duration',
    ];
}
