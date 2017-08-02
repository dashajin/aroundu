<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Emailcode extends Model
{
    protected $fillable = [
        'email', 'code',
    ];
}
