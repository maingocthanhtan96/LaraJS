<?php

namespace App\Services;

class FileService extends BaseService
{
    /**
     * @param $path
     * @param $fileName
     * @param $contents
     * @author tanmnt
     */
    public static function createFile($path, $fileName, $contents)
    {
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
        $path = $path . $fileName;
        file_put_contents($path, $contents);
    }

    /**
     * @param $path
     * @param $contents
     * @return bool
     * @author tanmnt
     */
    public static function createFileReal($path, $contents)
    {
        if (!file_exists($path)) {
            return false;
        }
        file_put_contents($path, $contents);
    }

    /**
     * @param $path
     * @param bool $replace
     * @author tanmnt
     */
    public static function createDirectoryIfNotExist($path, $replace = false)
    {
        if (file_exists($path) && $replace) {
            rmdir($path);
        }
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
    }

    /**
     * @param $path
     * @param $fileName
     * @return bool
     * @author tanmnt
     */
    public static function deleteFile($path, $fileName)
    {
        if (file_exists($path . $fileName)) {
            return unlink($path . $fileName);
        }
        return false;
    }
}
