<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Crud extends Model
{
    use HasFactory;

    protected $table = 'crud';
    public $timestamps = false;
    protected $fillable = ['name', 'telephone', 'email'];
}
