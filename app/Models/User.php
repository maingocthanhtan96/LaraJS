<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles, HasApiTokens, Notifiable, SoftDeletes;
    public const FOLDER_UPLOADS = '/uploads/users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'avatar', 'password'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Set permissions guard to API by default
     * @var string
     */
    protected $guard_name = 'api';

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        foreach ($this->roles as $role) {
            if ($role->isAdmin()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function isPermission(): bool
    {
        foreach ($this->getAllPermissions() as $permission) {
            if ($permission->isPermission()) {
                return true;
            }
        }

        return false;
    }

    //{{RELATIONS_NOT_DELETE_THIS_LINE}}
}
