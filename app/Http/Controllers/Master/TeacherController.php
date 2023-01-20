<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TeacherController extends Controller
{
    public function index(): JsonResponse {
        $teachers = User::whereType('P')
            ->whereActive('A')
            ->join('teachers AS t', 't.user_id', '=', 'users.id')
            ->get();

        return response()->json([
            'teachers' => $teachers
        ]);
    }

    public function show(Teacher $teacher): JsonResponse {
        $teacher = Teacher::where('teachers.id', $teacher->id)
            ->join('users', 'users.id', '=', 'teachers.user_id')
            ->first();

        return response()->json([
            'teacher' => $teacher
        ]);
    }

    public function store(Request $request): JsonResponse {
        try {
            $validator = Validator::make($request->only([
                'name','email', 'cpf', 'rg', 'birth_date', 'telephone', 'password'
            ]), [
                'name' => 'required|string',
                'email' => 'email|required|string|unique:users',
                'cpf' => 'required|string|unique:users',
                'rg' => 'string|unique:users',
                'birth_date' => 'required|string',
                'telephone' => 'required|string',
                'password' => 'required|string'
            ], [
                'name.required' => 'Nome é um campo obrigatório!',
                'email.required' => 'E-mail é um campo obrigatório!',
                'email.email' => 'O e-mail informado não é válido!',
                'email.unique' => 'O e-mail informado já está cadastrado em nossa base de dados!',
                'cpf.required' => 'CPF é um campo obrigatório',
                'cpf.unique' => 'CPF já está cadastrado em nossa base de dados!',
                'rg.unique' => 'RG já está cadastrado em nossa base de dados!',
                'birth_date.required' => 'Data de nascimento é um campo obrigatório',
                'telephone.required' => 'Telefone é um campo obrigatório!',
                'password.required' => 'Mensagem é um campo obrigatório!',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => $validator->errors()
                ]);
            }

            $user = new User([
                'name' => $request->name,
                'email' => $request->email,
                'cpf' => $request->cpf,
                'rg' => $request->rg,
                'birth_date' => $request->birth_date,
                'telephone' => $request->telephone,
                'password' => Hash::make($request->password),
                'active' => 1
            ]);
            $user->save();

            if ($user->id) {
                $teacher = new Teacher([
                    'user_id' => $user->id,
                    'type' => 'P'
                ]);
                $teacher->save();

                return response()->json([
                    'error' => false,
                    'message' => 'Professor cadastrado com sucesso!',
                    'student' => $teacher
                ]);
            }

            return response()->json([
                'error' => true,
                'message' => 'Aluno cadastrado. Tente novamente!',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function edit(int $id, Request $request): JsonResponse {
        try {
            $validator = Validator::make($request->only([
                'name','email', 'birth_date', 'telephone',
            ]), [
                'name' => 'required|string',
                'email' => 'email|required|string',
                'birth_date' => 'required|string',
                'telephone' => 'required|string',
            ], [
                'name.required' => 'Nome é um campo obrigatório!',
                'email.required' => 'E-mail é um campo obrigatório!',
                'email.email' => 'O e-mail informado não é válido!',
                'email.unique' => 'O e-mail informado já está cadastrado em nossa base de dados!',
                'birth_date.required' => 'Data de nascimento é um campo obrigatório',
                'telephone.required' => 'Telefone é um campo obrigatório!',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => $validator->errors()
                ]);
            }

            User::whereId($id)
                ->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'birth_date' => $request->birth_date,
                    'telephone' => $request->telephone,
                    'active' => 1
                ]);

//            Teacher::whereUserId($id)
//                ->update([
//                    'teacher_id' => $request->teacher_id
//                ]);

            return response()->json([
                'error' => false,
                'message' => 'Professor editado com sucesso!',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function destroy(Teacher $teacher): JsonResponse {
        try {
            if ($teacher->delete()) {
                User::whereId($teacher->user_id)->delete();
            }

            return response()->json([
                'error' => false,
                'message' => 'Professor deletado com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }
    }
}
