<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentsAudioCorrections extends Model
{
    use HasFactory;

    protected $fillable = [
        'students_audio_id', 'grade', 'description'
    ];
}
