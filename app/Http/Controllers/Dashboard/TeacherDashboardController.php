<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class TeacherDashboardController extends Controller
{
    public function countTeachers()
    {
        $count = User::join('teachers AS tc', 'users.id', '=', 'tc.user_id')
            ->where('tc.type', 'P')
            ->count();

        return response()->json([
            'count' => $count
        ]);
    }
}
