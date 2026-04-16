<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'img',
        'email',
        'password',
        'about'
    ];

    protected $hidden = [
        'password'
    ];

    public function billboards()
    {
        return $this->hasMany(Billboard::class);
    }

    public function orders()
    {
        return $this->hasManyThrough(Order::class, Billboard::class);
    }
}