<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class Admin extends Model implements MustVerifyEmail
{
    use HasFactory;

    protected $fillable = [
        'name',
        'img',
        'email',
        'password',
    ];
}
