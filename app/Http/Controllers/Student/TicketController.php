<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Ticket;
use App\Models\TicketMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller
{
    public function show(Student $student)
    {
        return response()->json([
            'error' => false,
            'tickets' => Ticket::whereStudentId($student->id)->get()
        ]);
    }

    public function readMessagesTicket(string $token) {
        $messages = Ticket::where('tickets.token', $token)
            ->join('students', 'students.id', '=', 'tickets.student_id')
            ->join('users', 'users.id', '=', 'students.user_id')
            ->join('ticket_messages', 'tickets.id', '=', 'ticket_messages.ticket_id')
            ->get();

        return response()->json([
            'error' => false,
            'messages' => $messages
        ]);
    }

    public function registerMessageTicket(Request $request) {
        try {
            $validator = Validator::make($request->only([
                'ticket_id', 'send_by', 'message'
            ]), [
                'ticket_id' => 'required',
                'send_by' => 'required|string',
                'message' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => $validator->errors()
                ]);
            }

            $ticket_message = new TicketMessage([
                'ticket_id' => $request->ticket_id,
                'send_by' => $request->send_by,
                'message' => $request->message
            ]);
            $ticket_message->save();

            return response()->json([
                'error' => false,
                'message' => 'Mensagem enviada. Retornaremos o mais breve possível!'
            ]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function store(Request $request) {
        try {
            $validator = Validator::make($request->only([
                'title', 'send_to', 'teacher_id', 'student_id', 'message'
            ]), [
                'title' => 'required|string',
                'send_to' => 'required|string',
                'student_id' => 'required',
                'message' => 'required|string',
            ], [
                'title.required' => 'Título é um campo obrigatório!',
                'send_to.required' => 'Enviar para é um campo obrigatório!',
                'student_id.required' => 'student_id é um campo obrigatório!',
                'message.required' => 'Mensagem é um campo obrigatório!',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => $validator->errors()
                ]);
            }

            $ticket = new Ticket([
                'teacher_id' => $request->teacher_id,
                'student_id' => $request->student_id,
                'title' => $request->title,
                'last_message' => $request->message,
                'send_to' => $request->send_to,
                'token' => date('YmdHis').uniqid()
            ]);
            $ticket->save();

            if ($ticket->id) {
                $ticket_message = new TicketMessage([
                    'ticket_id' => $ticket->id,
                    'send_by' => 'aluno',
                    'message' => $request->message
                ]);
                $ticket_message->save();
            }

            return response()->json([
                'error' => false,
                'message' => 'Ticket criado. Mensagem enviada!',
                'ticket' => $ticket
            ]);

        } catch (\Exception $exception) {
            return response()->json($exception->getMessage());
        }
    }
}
