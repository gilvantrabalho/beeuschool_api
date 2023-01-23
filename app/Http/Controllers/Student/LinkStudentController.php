<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Models\StudentLink;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LinkStudentController extends Controller
{
    public function index(int $id)
    {
        return response()->json([
            'links' => StudentLink::whereStudentId($id)->get()
        ]);
    }

    public function testeAction()
    {
        $links = Link::orWhere('permission', 'MS')
            ->orWhere('permission', 'T')
            ->get();

        return response()->json([
            'links' => $links
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->only([
                'name','url', 'description'
            ]), [
                'name' => 'required|string',
                'url' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => $validator->errors()
                ]);
            }

            $link = new StudentLink([
                'student_id' => $request->student_id,
                'name' => $request->name,
                'url' => $request->url,
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

    public function destroy(StudentLink $studentLink): JsonResponse
    {
        try {
            if ($studentLink->delete()) {
                return response()->json([
                    'error' => false,
                    'message' => 'Registro deletado com sucesso!'
                ]);
            }

            return response()->json([
                'error' => true,
                'message' => 'Erro ao tentar deletar o registro. Tente novamente!'
            ]);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
