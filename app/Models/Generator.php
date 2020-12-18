<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Generator extends Model
{
    use SoftDeletes;

    protected $table = 'generators';
    protected $fillable = ['field', 'model', 'table', 'files'];
}
