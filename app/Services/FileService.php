<?php

namespace App\Service;

Class FileService extends BaseService
{
	public static function createFile($path, $fileName, $contents)
	{
		if (!file_exists($path)) {
			mkdir($path, 0755, true);
		}
		$path = $path.$fileName;
		file_put_contents($path, $contents);
	}
	public static function createFileReal($path, $contents)
	{
		if (!file_exists($path)) {
			return false;
		}
		file_put_contents($path, $contents);
	}
	public static function createDirectoryIfNotExist($path, $replace = false)
	{
		if (file_exists($path) && $replace) {
			rmdir($path);
		}
		if (!file_exists($path)) {
			mkdir($path, 0755, true);
		}
	}
	public static function deleteFile($path, $fileName)
	{
		if (file_exists($path.$fileName)) {
			return unlink($path.$fileName);
		}
		return false;
	}
}