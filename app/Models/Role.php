<?php

namespace App\Models;

use \App\Larajs\Permission as LarajsPermission;

class Role extends \Spatie\Permission\Models\Role
{
    /**
     * Check whether current role is admin
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->name === LarajsPermission::ROLE_ADMIN;
    }
}
