<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegisterHour extends Model
{
    use HasFactory;
    protected $table = 'register_hour';
    public $timestamps = false;
}
