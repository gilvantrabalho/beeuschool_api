<?php

namespace App\Http\Controllers\Visitor;

use App\Http\Controllers\Controller;
use App\Models\User\UserContact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function store(Request $request): JsonResponse {
        try {

            $validator = Validator::make($request->only(['name', 'telephone', 'email', 'message']), [
                'name' => 'required|string',
                'telephone' => 'required|string',
                'email' => 'email|string',
                'message' => 'required|string'
            ], [
                'name.required' => 'Nome é um campo obrigatório!',
                'telephone.required' => 'Telefone é um campo obrigatório!',
                'email.email' => 'O e-mail informado não é válido!',
                'message.required' => 'Mensagem é um campo obrigatório!'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => $validator->errors()
                ]);
            }

            $user_contact = new UserContact([
                'name' => $request->name,
                'telephone' => $request->telephone,
                'email' => $request->email,
                'message' => $request->message,
                'status' => 0
            ]);
            $user_contact->save();

            return response()->json([
                'error' => false,
                'message' => 'Sua mensagem foi enviada com sucesso. Em breve retornaremos seu contato!',
                'contact' => $user_contact
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }
    }
}
