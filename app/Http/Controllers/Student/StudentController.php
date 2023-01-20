<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function index(): JsonResponse {
        return response()->json([
            'actives' => Student::whereType('A')->whereActive('A')->count(),
            'inactives' => Student::whereType('A')->whereActive('I')->count(),
            'students' => Student::whereType('A')->get()
        ]);
    }

    public function show(Student $student): JsonResponse {
        try {
            if (!$student) {
                return response()->json([
                    'error' => true,
                    'message' => 'Aluno não encontrado!'
                ]);
            }
            return response()->json([
                'student' => $student
            ]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function store(Request $request): JsonResponse {
        try {
            $validator = Validator::make($request->only([
                'name','email', 'cpf', 'rg', 'birth_date', 'telephone', 'teacher_code', 'password'
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

            $student = new Student([
                'name' => $request->name,
                'email' => $request->email,
                'cpf' => $request->cpf,
                'rg' => $request->rg,
                'birth_date' => $request->birth_date,
                'telephone' => $request->telephone,
                'teacher_code' => $request->teacher_code,
                'password' => Hash::make($request->password),
                'active' => 1,
                'type' => 'A'
            ]);
            $student->save();

            return response()->json([
                'error' => false,
                'message' => 'Aluno cadastrado com sucesso!',
                'student' => $student
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
                'message' => 'Aluno editado com sucesso!',
            ]);

        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function delete(Student $student) {
        try {
            if ($student->delete()) {
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
