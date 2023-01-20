<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'student_id',
        'title',
        'last_message',
        'send_to',
        'token'
    ];

    public function ticket_messages() {
        return $this->hasMany(TicketMessage::class);
    }

    public function student() {
        return $this->hasOne(Student::class, 'student_id');
    }
}
