<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $table = 'students';

    protected $fillable = [
        'user_id',
        'teacher_id',
        'plan_id',
        'day_payment',
        'type'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function ticket() {
        return $this->belongsTo(Ticket::class);
    }
}
