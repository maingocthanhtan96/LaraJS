<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class PasswordReset extends BaseModel
{
    use HasFactory;

    const EXPIRE_TOKEN = 60;

    public $timestamps = false;
    protected $primaryKey = null;
    protected $table = 'password_resets';
    protected $fillable = ['email', 'token', 'created_at'];
}
