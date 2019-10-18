<?php
/**
 * File Permission.php
 *
 * @author Tan Mai <maingocthanhtan96@gmail.com>
 * @package Larajs
 * @version 1.0
 */
namespace App\Larajs;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * Class Acl
 *
 * @package App\Larajs
 */
final class Permission
{
	const ROLE_ADMIN = 'admin';
    const ROLE_MANAGER = 'manager';
    const ROLE_VISITOR = 'visitor';
    const ROLE_EDITOR = 'editor';
    const ROLE_CREATOR = 'creator';

    const PERMISSION_PERMISSION_MANAGE = 'manage permission';
    const PERMISSION_VISIT = 'visit';
    const PERMISSION_CREATE = 'create';
    const PERMISSION_EDIT = 'edit';
    const PERMISSION_ROUTE = 'route';

	/**
	 * @param array $exclusives Exclude some permissions from the list
	 * @return array
	 */
	public static function permissions(array $exclusives = []): array
	{
		try {
			$class = new \ReflectionClass(__CLASS__);
			$constants = $class->getConstants();
			$permissions = Arr::where($constants, function($value, $key) use ($exclusives) {
				return !in_array($value, $exclusives) && Str::startsWith($key, 'PERMISSION_');
			});

			return array_values($permissions);
		} catch (\ReflectionException $exception) {
			return [];
		}
	}

	public static function menuPermissions(): array
	{
		try {
			$class = new \ReflectionClass(__CLASS__);
			$constants = $class->getConstants();
			$permissions = Arr::where($constants, function($value, $key) {
				return Str::startsWith($key, 'PERMISSION_VIEW_MENU_');
			});

			return array_values($permissions);
		} catch (\ReflectionException $exception) {
			return [];
		}
	}

	/**
	 * @return array
	 */
	public static function roles(): array
	{
		try {
			$class = new \ReflectionClass(__CLASS__);
			$constants = $class->getConstants();
			$roles = Arr::where($constants, function($value, $key) {
				return Str::startsWith($key, 'ROLE_');
			});

			return array_values($roles);
		} catch (\ReflectionException $exception) {
			return [];
		}
	}

	public static function authRoles(): string
    {
        $roles = self::roles();
        $str = '';
        foreach($roles as $key => $role) {
            if(count($roles) - 1 === $key) {
                $str .= $role;
            } else {
                $str .= $role . '|';
            }
        }
        return $str;
    }
}
