<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class TeacherAudioRepository {
    public static function getStudentsAudioByTeacherIdAndStatus(int $id, string $status) {
        return DB::select("SELECT *, otx.name AS otx_name, stda.id AS id, stda.created_at
            FROM students_audio AS stda
            INNER JOIN one_hundred_texts AS otx
                ON otx.id = stda.one_hundred_texts_id
            INNER JOIN students AS std
                ON std.id = stda.student_id
            INNER JOIN users AS u
                ON u.id = std.user_id
            WHERE stda.teacher_id = {$id}
                AND stda.status = '{$status}'");
    }

}
