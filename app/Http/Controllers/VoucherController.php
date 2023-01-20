<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Voucher;
use App\Repositories\VoucherRepository;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function show(Voucher $voucher) {
        $res = Voucher::join('students AS std', 'std.id', '=', 'vouchers.student_id')
            ->join('users AS u', 'u.id', '=', 'std.user_id')
            ->where('vouchers.id', $voucher->id)
            ->first();

        return response()->json([
            'voucher' => $res
        ]);
    }

    public function configVoucher(Student $student)
    {
        $date_now = date('Y-m-d', strtotime('now'));
        $user = Student::where('students.id', $student->id)
            ->join('users', 'users.id', '=', 'students.user_id')
            ->first();

        $vouchers_student = Voucher::whereStudentId($student->id)->whereStatus('Em Aberto')->get();
        $vouchers_atrasado = Voucher::whereStudentId($student->id)->whereStatus('Atrasado')->count();

        foreach ($vouchers_student AS $item) {
            $database = date_create($item->due_date);
            $datadehoje = date_create();
            $resultado = date_diff($database, $datadehoje);

            //  DEFINIR MENSALIDADE A VENCER
            if (($resultado->d + 1) < 10) {
                Voucher::whereId($item->id)->update([
                    'status' => 'A Vencer'
               ]);
            }

            echo $date_now . ' - ' . $item->due_date . '<br>';

            //  DEFINIR MENSALIDADE EM ATRASO
            if ($date_now > $item->due_date) {
                Voucher::whereId($item->id)->update([
                    'status' => 'Atrasado'
                ]);

                return response()->json([
                    'res' => $date_now > $item->due_date,
                    'now' => $date_now,
                    'due' => $item->due_date
                ]);
            }
        }

        //  Bloquear conta de aluno: active
        if ($vouchers_atrasado == 2) {
            User::whereId($user->user_id)->update([
                'active' => 'I'
            ]);
        }
    }

    public function readByStudentId(Student $student)
    {
        return response()->json([
            'vouchers' => VoucherRepository::getByStudentId($student->id)
        ]);
    }

    public function store(Request $request) {
        try {
            $request->only('id');
            $file = $request->file('file');
            $path = $file->store('comprovantes', 'public');

            Voucher::whereId($request->id)
                ->update([
                    'status' => 'Enviado',
                    'file' => $path,
                    'sent_in' => date('Y-m-d'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

            return response()->json([
                'error' => false,
                'message' => 'Comprovante enviado. Aguarde aprovação!'
            ]);

        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function validateUpdate(Request $request) {
        try {
            $request->only('id', 'transaction_key');

            Voucher::whereId($request->id)
                ->update([
                    'status' => 'Pago',
                    'transaction_key' => $request->transaction_key,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

            return response()->json([
                'error' => false,
                'message' => 'Comprovante validado com sucesso!'
            ]);

        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function invalidateUpdate(Request $request) {
        try {
            $request->only('id', 'observ');

            Voucher::whereId($request->id)
                ->update([
                    'status' => 'Recusado',
                    'observation' => $request->observ,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

            return response()->json([
                'error' => false,
                'message' => 'Observação enviado para o aluno!'
            ]);

        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function readByGrid()
    {
        return response()->json([
            'vouchers' => Voucher::whereStatus(['Enviado', 'Pago'])->get()
        ]);
    }

    public function readByStatus(string $status)
    {
        $vouchers = Voucher::
            select('vouchers.id', 'u.name', 'u.telephone', 'vouchers.month_reference',
                'pl.name AS plan', 'pl.value AS value')
            ->join('students AS std', 'std.id', '=', 'vouchers.student_id')
            ->join('users AS u', 'u.id', '=', 'std.user_id')
            ->join('plans AS pl', 'pl.id', '=', 'std.plan_id')
            ->where('vouchers.status', $status)
            ->get();

        return response()->json([
            'vouchers' => $vouchers
        ]);
    }

    public static function generator(string $dayVenc, int $student_id)
    {
        $arrayDate = [];
        $arrayMeses = [
            "January" => "Janeiro",
            "February" => "Fevereiro",
            "March" => "Março",
            "April" => "Abril",
            "May" => "Maio",
            "June" => "Junho",
            "July" => "Julho",
            "August" => "Agosto",
            "September" => "Setembro",
            "October" => "Outubro",
            "November" => "Novembro",
            "December" => "Dezembro"
        ];

        array_push(
            $arrayDate,
        date('Y-m-d', strtotime('+2 day'))
        );
        for($i=1; $i <= 12; $i++) {
            array_push(
                $arrayDate,
                date('Y-m-', strtotime("+ {$i} month")) . $dayVenc
            );
        }

        foreach ($arrayDate as $item) {
            $voucher = new Voucher([
                'student_id' => $student_id,
                'month_reference' => $arrayMeses[date('F', strtotime($item))],
                'due_date' => $item,
                'day_payment' => $dayVenc,
                'status' => 'Em Aberto'
            ]);
            $voucher->save();
        }
    }
}
