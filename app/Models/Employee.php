<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    // タイムスタンプを自動で管理する
    public $timestamps = true; // これがデフォルトでtrueになっているはずです

    protected $fillable = [
        'employee_id',
        'employee_name',
        'gender',
        'birthday',
        'email',
    ];
}
