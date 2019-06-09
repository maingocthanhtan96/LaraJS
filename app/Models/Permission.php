<?php

namespace App\Models;

use \App\Larajs\Permission as LarajsPermission;

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
		return $query->where('name', '!=', LarajsPermission::PERMISSION_PERMISSION_MANAGE);
	}
}
