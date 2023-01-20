<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class VideoController extends Controller
{
    public function index(): JsonResponse {
        return response()->json([
            'videos' => Video::all()
        ]);
    }

    public function show(Video $video): JsonResponse {
        try {
            if (!$video) {
                return response()->json([
                    'error' => true,
                    'message' => 'Vídeo não encontrado!'
                ]);
            }
            return response()->json([
                'video' => $video
            ]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->only([
                'title', 'video_code', 'duration', 'description'
            ]), [
                'title' => 'required|string',
                'video_code' => 'required|string',
                'duration' => 'required|string',
                'description' => 'required|string',
            ], [
                'title.required' => 'Título é um campo obrigatório!',
                'video_code.required' => 'Código do vídeo é um campo obrigatório!',
                'duration.required' => 'Duração é um campo obrigatório!',
                'description.required' => 'Descrição é um campo obrigatório!'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => $validator->errors()
                ]);
            }

            $video = new Video([
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'video_code' => $request->video_code,
                'duration' => $request->duration,
                'description' => $request->description,
            ]);
            $video->save();

            return response()->json([
                'error' => false,
                'message' => 'Vídeo cadastrado com sucesso!',
                'student' => $video
            ]);

        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function update(int $id, Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->only([
                'title', 'video_code', 'duration', 'description'
            ]), [
                'title' => 'required|string',
                'video_code' => 'required|string',
                'duration' => 'required|string',
                'description' => 'required|string',
            ], [
                'title.required' => 'Título é um campo obrigatório!',
                'video_code.required' => 'Código do vídeo é um campo obrigatório!',
                'duration.required' => 'Duração é um campo obrigatório!',
                'description.required' => 'Descrição é um campo obrigatório!'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => $validator->errors()
                ]);
            }

            Video::whereId($id)
                ->update([
                    'title' => $request->title,
                    'video_code' => $request->video_code,
                    'duration' => $request->duration,
                    'description' => $request->description,
                ]);

            return response()->json([
                'error' => false,
                'message' => 'Vídeo cadastrado com sucesso!',
            ]);

        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function destroy(Video $video): JsonResponse
    {
        try {
            if ($video->delete()) {
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
