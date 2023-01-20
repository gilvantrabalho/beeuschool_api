<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketMasterController extends Controller
{
    public function index()
    {
        try {
            $tickets = Ticket::join('students', 'students.id', '=', 'tickets.student_id')
                ->join('users', 'users.id', '=', 'students.user_id')
                ->get();

            return response()->json([
                'error' => false,
                'tickets' => $tickets
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
