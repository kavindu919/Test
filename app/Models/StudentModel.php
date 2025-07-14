<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentModel extends Model
{
    protected $table = "students";
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'gender',
        'birthdate',
        'soft_delete'
    ];
}
