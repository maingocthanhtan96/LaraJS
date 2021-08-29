<?php

namespace App\Generators;

use App\Services\FileService;
use App\Services\GeneratorService;

class BaseGenerator
{
    /** @var GeneratorService $service */
    protected GeneratorService $serviceGenerator;

    /** @var FileService $service */
    protected FileService $serviceFile;

    /** @var string */
    protected string $path;

    /** @var array  */
    protected array $notDelete;

    public function __construct()
    {
        $this->serviceGenerator = new GeneratorService();
        $this->serviceFile = new FileService();
    }

    public function rollbackFile($path, $fileName): bool
    {
        if (file_exists($path . $fileName)) {
            return FileService::deleteFile($path, $fileName);
        }
        return false;
    }
}
