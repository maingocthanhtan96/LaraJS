<?php

namespace App\Models;

class Role extends \Spatie\Permission\Models\Role
{
    /**
     * Check whether current role is admin
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->name === \ACL::ROLE_ADMIN;
    }
}
