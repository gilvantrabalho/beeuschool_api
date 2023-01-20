<?php

namespace App\Repositories;

use App\Models\User;

class TeacherRepository {

    public static function getByActive(string $active) {
        return User::join('teachers AS tc', 'users.id', '=', 'tc.user_id')
            ->where('users.active', $active)
            ->where('tc.type', 'P')
            ->count();
    }

}
