<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Repositories\TeacherRepository;

class TeacherController extends Controller
{
    public function index(): JsonResponse {
        return response()->json([
            'actives' => TeacherRepository::getByActive('A'),
            'inactives' => TeacherRepository::getByActive('I'),
            'teachers' => Teacher::with('user')->get()
        ]);
    }

    public function show(Teacher $teacher): JsonResponse {
        try {
            if (!$teacher) {
                return response()->json([
                    'error' => true,
                    'message' => 'Professor não encontrado!'
                ]);
            }
            return response()->json([
                'student' => $teacher
            ]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
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
                    'teacher' => User::with('teacher')->whereId($user->id)->first()
                ]);
            }

            return response()->json([
                'error' => true,
                'message' => 'Erro ao tentar cadastrar professor. Tente novamente!',
                'student' => ''
            ]);

        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function update(int $id, Request $request): JsonResponse {
        try {
            $validator = Validator::make($request->only([
                'name','email', 'cpf', 'rg', 'birth_date', 'telephone', 'teacher_code', 'active'
            ]), [
                'name' => 'required|string',
                'email' => 'email|required|string',
                'cpf' => 'required|string',
                'rg' => 'string',
                'birth_date' => 'required|string',
                'telephone' => 'required|string',
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
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => $validator->errors()
                ]);
            }

            Student::whereId($id)
                ->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'cpf' => $request->cpf,
                    'rg' => $request->rg,
                    'birth_date' => $request->birth_date,
                    'telephone' => $request->telephone,
                    'teacher_code' => $request->teacher_code,
                    'active' => $request->active
                ]);

            return response()->json([
                'error' => false,
                'message' => 'Professor editado com sucesso!',
            ]);

        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function delete(Teacher $teacher): JsonResponse {
        try {
            if ($teacher->with('user')->delete()) {
                return response()->json([
                    'error' => false,
                    'message' => 'Professor deletado com sucesso!'
                ]);
            }
        } catch (\Exception $e) {
            return  response()->json($e->getMessage());
        }
    }
}
