<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'telephone',
        'email',
        'message',
        'status',
    ];
}
