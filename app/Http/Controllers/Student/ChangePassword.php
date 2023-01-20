<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ChangePassword extends Controller
{
    public function index(Request $request) {
        try {
            $validator = Validator::make($request->only([
                'password','new_password', 'c_passsword'
            ]), [
                'password' => 'required|string',
                'new_password' => 'required|string',
                'c_passsword' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => $validator->errors()
                ]);
            }

            $student = Student::where('students.id', $request->user_id)
                ->join('users AS u', 'u.id', '=', 'students.user_id')
                ->first();


            if (crypt($request->password, $student->password) == $student->password) {
                User::whereId($student->user_id)->update([
                    'password' => Hash::make($request->new_password)
                ]);
                return response()->json([
                    'error' => false,
                    'message' => 'Senha alterada com sucesso!'
                ]);
            } else {
                return response()->json([
                    'error' => true,
                    'message' => 'Senha atual incorreta!'
                ]);
            }

        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
