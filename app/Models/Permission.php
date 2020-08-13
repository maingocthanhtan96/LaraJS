<?php

namespace App\Models;

class Permission extends \Spatie\Permission\Models\Permission
{
    /**
     * To exclude permission management from the list
     *
     * @param $query
     * @return Builder
     */
    public function scopeAllowed($query)
    {
        return $query->where('name', '!=', \ACL::PERMISSION_PERMISSION_MANAGE);
    }

    /**
     * @return bool
     */
    public function isPermission(): bool
    {
        return $this->name === \ACL::PERMISSION_PERMISSION_MANAGE;
    }
}
