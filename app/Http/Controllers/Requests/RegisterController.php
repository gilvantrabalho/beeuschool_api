<?php

namespace App\Http\Controllers\Requests;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public static function store($model, Request $request, $fields, $rules = [], $messages = [])
    {
        try {
            $validateFields = array_combine($fields, $rules);
            $validator = Validator::make($request->only($fields), $validateFields, $messages);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'errors' => $validator->errors()
                ]);
            }

            $resp = $model->create($request->only($fields));
            return response()->json([
                'error' => false,
                'message' => 'Registro cadastrado com sucesso!',
                'register' => $resp
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }
    }
}
