<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LastLinksAccessedStudent extends Model
{
    use HasFactory;

    public $table = 'last_links_accessed_student';
    protected $fillable = ['student_id', 'link_id'];
}
