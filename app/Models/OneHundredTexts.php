<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OneHundredTexts extends Model
{
    use HasFactory;

//    public function student_audio()
//    {
//        return $this->hasOne(StudentsAudio::class);
//    }

    // "SQLSTATE[42S22]: Column not found: 1054 Unknown column 'one_hundred_texts.students_audio_id' in 'where clause'"
    // (SQL: select * from `one_hundred_texts` where `one_hundred_texts`.`students_audio_id` in (1))
}
