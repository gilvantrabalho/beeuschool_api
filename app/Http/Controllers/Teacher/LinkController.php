<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Link;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LinkController extends Controller
{
    public function index(): JsonResponse {
        return response()->json([
            'links' => Link::all()
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->only([
                'name','description'
            ]), [
                'name' => 'required|string',
                'description' => 'required|string',
            ], [
                'name.required' => 'Nome é um campo obrigatório!',
                'description.required' => 'Descrição é um campo obrigatório!'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => $validator->errors()
                ]);
            }

            $link = new Link([
                'name' => $request->name,
                'description' => $request->description,
            ]);
            $link->save();

            return response()->json([
                'error' => false,
                'message' => 'Link cadastrado com sucesso!',
                'student' => $link
            ]);

        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function show(Link $link): JsonResponse {
        try {
            if (!$link) {
                return response()->json([
                    'error' => true,
                    'message' => 'Link não encontrado!'
                ]);
            }
            return response()->json([
                'link' => $link
            ]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function update(int $id, Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->only([
                'name','description'
            ]), [
                'name' => 'required|string',
                'description' => 'required|string',
            ], [
                'name.required' => 'Nome é um campo obrigatório!',
                'description.required' => 'Descrição é um campo obrigatório!'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => $validator->errors()
                ]);
            }

            Link::whereId($id)
                ->update([
                    'name' => $request->name,
                    'description' => $request->description,
                ]);

            return response()->json([
                'error' => false,
                'message' => 'Link editado com sucesso!',
            ]);

        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function destroy(Link $link): JsonResponse
    {
        try {
            if ($link->delete()) {
                return response()->json([
                    'error' => false,
                    'message' => 'Registro deletado com sucesso!'
                ]);
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
