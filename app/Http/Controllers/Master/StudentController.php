<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Controllers\VoucherController;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function index(): JsonResponse {
        return response()->json([
            'students' => User::join('students AS st', 'st.user_id', '=', 'users.id')->get()
        ]);
    }

    public function show(Student $student): JsonResponse {
        $student = Student::where('students.id', $student->id)
            ->join('users', 'users.id', '=', 'students.user_id')
            ->first();

        return response()->json([
            'student' => $student
        ]);
    }

    public function store(Request $request): JsonResponse {
        try {
            $validator = Validator::make($request->only([
                'name','email', 'cpf', 'rg', 'birth_date',
                'telephone', 'password', 'teacher_id', 'plan_id', 'day_payment'
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

                $student = new Student([
                    'user_id' => $user->id,
                    'teacher_id' => $request->teacher_id,
                    'plan_id' => $request->plan_id,
                    'day_payment' => $request->day_payment,
                    'type' => 'A'
                ]);
                $student->save();

                VoucherController::generator(
                    $request->day_payment,
                    $student->id
                );

                return response()->json([
                    'error' => false,
                    'message' => 'Aluno cadastrado com sucesso!',
                    'student' => $student
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
                'name','email', 'birth_date', 'telephone', 'teacher_id'
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

            Student::whereUserId($id)
                ->update([
                    'teacher_id' => $request->teacher_id
                ]);

            return response()->json([
                'error' => false,
                'message' => 'Aluno editado com sucesso!',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function destroy(Student $student): JsonResponse {
        try {
            if ($student->delete()) {
                User::whereId($student->user_id)->delete();
            }

            return response()->json([
                'error' => false,
                'message' => 'Aluno deletado com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
