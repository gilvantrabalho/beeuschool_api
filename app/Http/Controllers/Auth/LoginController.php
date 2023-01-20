<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function index(Request $request): JsonResponse {
        try {
            $credentials = $request->only('email', 'password');

            $validator = Validator::make($credentials, [
                'email' => 'required|string',
                'password' => 'required'
            ], [
                'email.required' => 'E-mail é um campo obrigatório.',
                'password.required' => 'Senha é um campo obrigatório.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => $validator->errors()
                ]);
            }

            if (!auth()->attempt($credentials)) {
                return response()->json([
                    'error' => true,
                    'response' => [
                        'message' => "Email e/ou senha incorreto!"
                    ]
                ]);
            }

            $token = auth()->user()->createToken('auth_token');

            return response()->json([
                'error' => false,
                'response' => [
                    'message' => 'Usuário encontrado com sucesso!',
                    'token' => $token->plainTextToken
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }
    }
}
