<?php

namespace App\Models;

class Permission extends \Spatie\Permission\Models\Permission
{
    const VIEW_MENU = 'view menu ';

    /**
     * @return bool
     */
    public function isPermission(): bool
    {
        return $this->name === \ACL::PERMISSION_PERMISSION_MANAGE;
    }
}
