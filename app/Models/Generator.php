<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Generator extends Model
{
    protected $table = 'generators';
    protected $fillable = [
        'field',
        'model',
        'table',
    ];
}
