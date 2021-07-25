<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Generator extends BaseModel
{
    use SoftDeletes;

    const NUMBER_FILE_DELETES = 10;

    protected $table = 'generators';
    protected $fillable = ['field', 'model', 'table', 'files'];
}
