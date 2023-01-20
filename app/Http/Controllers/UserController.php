<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function userByToken(Request $request): JsonResponse {
        $user = User::where('users.id', $request->user()->id)
            ->join('students', 'students.user_id', '=', 'users.id')->first();

        if (!$user)
            $user = User::where('users.id', $request->user()->id)
                ->join('teachers', 'teachers.user_id', '=', 'users.id')->first();

        return response()->json([
            'user' => $user
        ]);
    }
}
