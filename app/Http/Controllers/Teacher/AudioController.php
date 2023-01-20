<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\StudentsAudio;
use App\Repositories\TeacherAudioRepository;

class AudioController extends Controller
{
    public function studentsAudio(int $teacher_id)
    {
        return response()->json([ // teacher_id
            'audios' => TeacherAudioRepository::getStudentsAudioByTeacherIdAndStatus($teacher_id, 'E')
        ]);
    }
}
