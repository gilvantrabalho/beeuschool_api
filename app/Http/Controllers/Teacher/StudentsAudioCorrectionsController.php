<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\StudentsAudio;
use App\Models\StudentsAudioCorrections;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentsAudioCorrectionsController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        try {

            $validator = Validator::make($request->only([
                'students_audio_id', 'status', 'grade', 'description'
            ]), [
                'students_audio_id' => 'required',
                'description' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => $validator->errors()
                ]);
            }

            $studentsAudioCorrections = new StudentsAudioCorrections([
                'students_audio_id' => $request->students_audio_id,
                'grade' => $request->grade ?? 'SN',
                'description' => $request->description
            ]);
            $studentsAudioCorrections->save();

            StudentsAudio::whereId($request->students_audio_id)
                ->update([
                    'status' => $request->status
                ]);

            return response()->json([
                'error' => false,
                'message' => 'Correção enviado para o aluno!'
            ]);

        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
