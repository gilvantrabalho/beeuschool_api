<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentsAudio;
use App\Models\StudentsAudioCorrections;
use App\Repositories\StudentsAudioRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AudioController extends Controller
{
    public function countAudios(Student $student)
    {
        return response()->json([
            'count_audios' => StudentsAudio::whereStudentId($student->id)->count()
        ]);
    }

    public function show(int $id): JsonResponse {
        try {
            return response()->json([
                'audios' => StudentsAudioRepository::getAllByUserId($id)
            ]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function showStudentsAudioId(int $id) {
        return response()->json([
            'student_audio' => StudentsAudioRepository::getStudentsAudioById($id)
        ]);
    }

    public function store(Request $request): JsonResponse {
        try {
            $request->only('student_id', 'teacher_id','text_ref_id','title', 'description');
            $file = $request->file('file');
            $path = $file->store('audios', 'public');

            $students_audio = new StudentsAudio([
                'student_id' => $request->student_id,
                'teacher_id' => $request->teacher_id,
                'one_hundred_texts_id' => $request->text_ref_id,
                'title' => 'Não usado',
                'file' => $path,
                'description' => $request->description,
                'status' => 'E'
            ]);
            $students_audio->save();

            if ($students_audio->id) {
                return response()->json([
                    'error' => false,
                    'message' => 'Áudio enviado para o professor com sucesso!'
                ]);
            }

            return response()->json([
                'error' => true,
                'message' => 'Falha enviado para o professor. Tente novamente!'
            ]);

        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function update(Request $request): JsonResponse {
        try {
            $file = $request->file('file');
            $path = $file->store('audios', 'public');

            Storage::disk('public')->delete($request->file_name);

            StudentsAudio::whereId($request->id)
                ->update([
                    'file' => $path,
                    'status' => 'E'
                ]);
            StudentsAudioCorrections::whereId($request->idSac)
                ->delete();

            return response()->json([
                'error' => false,
                'message' => 'Novo áudio enviado para o professor!'
            ]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
