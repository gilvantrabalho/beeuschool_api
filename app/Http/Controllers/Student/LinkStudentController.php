<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Requests\RegisterController;
use App\Models\LastLinksAccessedStudent;
use App\Models\Link;
use App\Models\Student;
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

    public function readLastLinksAccessed(Student $student) {
        $lasts = LastLinksAccessedStudent::where('last_links_accessed_student.student_id', $student->id)
            ->join('links AS l', 'l.id', '=', 'last_links_accessed_student.link_id')
            ->orderBy('last_links_accessed_student.id', 'DESC')
            ->get();
        return response()->json([
            'links' => $lasts
        ]);
    }

    public function lastLinksAccessed(Request $request)
    {
        $verify = LastLinksAccessedStudent::whereLinkId($request->link_id)
            ->whereStudentId($request->student_id)->first();

        if ($verify) {
            return response()->json([
                'message' => 'Registro já cadastrado!'
            ]);
        }

        if (LastLinksAccessedStudent::whereStudentId($request->student_id)->count() == 5) {
            $last = LastLinksAccessedStudent::whereStudentId($request->student_id)->first();
            LastLinksAccessedStudent::whereId($last->id)->delete();
        }

        return RegisterController::store(
            new LastLinksAccessedStudent(),
            $request,
            ['student_id', 'link_id'],
            [
                'required',
                'required'
            ]
        );
    }
}
