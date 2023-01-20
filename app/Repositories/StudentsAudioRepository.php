<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class StudentsAudioRepository {

    public static function getAllByUserId(int $id) {
        return DB::select("SELECT *, std.id AS id, std.created_at
            FROM students_audio AS std
            INNER JOIN one_hundred_texts AS otx
            ON otx.id = std.one_hundred_texts_id
                WHERE std.student_id = {$id}
                ORDER BY otx.order");
    }

    public static function getStudentsAudioById(int $id) {
        return DB::select("SELECT *, std.id AS id, sac.id AS idSac,
            std.description AS description,std.created_at, sac.description AS desc_teacher
            FROM students_audio AS std
            INNER JOIN one_hundred_texts AS otx
                ON otx.id = std.one_hundred_texts_id
            LEFT JOIN students_audio_corrections AS sac
                ON std.id = sac.students_audio_id
                    WHERE std.id = {$id}");
    }

}
