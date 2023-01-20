<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentsAudio extends Model
{
    use HasFactory;
    protected $table = 'students_audio';

    protected $fillable = [
        'student_id',
        'teacher_id',
        'one_hundred_texts_id',
        'title',
        'file',
        'description',
        'status'
    ];

    public function one_hundred_text() {
        return $this->hasOne(OneHundredTexts::class);
    }
}
