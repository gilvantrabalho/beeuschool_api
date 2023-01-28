<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\Request;

class StudentsDashboardController extends Controller
{
    public function countStudents()
    {
        $count = User::join('students AS st', 'users.id', '=', 'st.user_id')
            ->count();
        return response()->json([
            'count' => $count
        ]);
    }

    public function lastRegisteredStudents()
    {
        $students = User::join('students AS st', 'users.id', '=', 'st.user_id')
            ->orderBy('st.created_at', 'DESC')
            ->limit(5)
            ->get();
        return response()->json([
            'students' => $students
        ]);
    }

    public function showStudentPlan(Plan $plan) {
        return response()->json([
            'plan' => $plan
        ]);
    }
}
