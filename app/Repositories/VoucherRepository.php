<?php

namespace App\Repositories;

use App\Models\Voucher;

class VoucherRepository
{
    public static function getByStudentId(int $id)
    {
        return Voucher::select('*', 'vouchers.id AS id')
            ->where('vouchers.status', 'Em Aberto')
            ->orWhere('vouchers.status', 'Atrasado')
            ->orWhere('vouchers.status', 'Enviado')
            ->orWhere('vouchers.status', 'A Vencer')
            ->where('vouchers.student_id', $id)
            ->join('students', 'students.id', '=', 'vouchers.student_id')
            ->join('plans', 'plans.id', '=', 'students.plan_id')
            ->orderBy('vouchers.due_date', 'ASC')
            ->get();
    }
}
