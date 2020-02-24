<?php

namespace App\Console\Commands;

use File;
use phpDocumentor\GraphViz\Exception;
use ZipArchive;
use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class BackupMySQLCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:mysql {--command= : <create|restore> command to execute} {--snapshot= : provide name of snapshot}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Weekly MySQL backup';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        switch ($this->option('command')) {
            case 'create':
                $this->takeSnapShot();
                break;

            case 'restore':
                $this->restoreSnapShot();
                break;

            default:
                $this->error('Invalid Option !!');
                break;
        }
    }

    /**
     * Function takes regular backup
     * for mysql database..
     *
     */
    private function takeSnapShot()
    {
        set_time_limit(0);
        $dbName = env('DB_DATABASE') . '_' . date('Y-m-d_Hi');
        // define target file
        $tempLocation = '/tmp/' . $dbName . '.sql';

        // run the cli job
        $process = new Process(
            'mysqldump -u' .
                env('DB_USERNAME') .
                ' -p' .
                env('DB_PASSWORD') .
                ' ' .
                env('DB_DATABASE') .
                ' > ' .
                $tempLocation,
        );
        $process->run();

        try {
            if ($process->isSuccessful()) {
                $zip = new ZipArchive();
                if ($zip->open(storage_path("app/mysql/{$dbName}.zip"), ZipArchive::CREATE) === true) {
                    // Add files to the zip file
                    $zip->addFile($tempLocation, $dbName . '.sql');
                    // All files are added, so close the zip file.
                    $zip->close();
                }

                $currentTimestamp = time() - 168 * 3600; // 7 days
                $s3 = \Storage::disk();
                $allFiles = $s3->allFiles('mysql');
                foreach ($allFiles as $file) {
                    // delete the files older then 7 days..
                    if ($s3->lastModified($file) <= $currentTimestamp) {
                        $s3->delete($file);
                        $this->info("File: {$file} deleted.");
                    }
                }
            } else {
                throw new ProcessFailedException($process);
            }

            @unlink($tempLocation);
        } catch (\Exception $e) {
            $this->info($e->getMessage(), $e->getFile(), $e->getLine());
        }
    }

    /**
     * Function restore given snapshot
     * for mysql database
     */
    private function restoreSnapShot()
    {
        $snapshot = $this->option('snapshot');
        if (!$snapshot) {
            $this->error('snapshot option is required.');
        }

        try {
            $zip = new ZipArchive();
            $res = $zip->open(storage_path("app/mysql/{$snapshot}.zip"));
            if ($res === true) {
                $zip->extractTo(storage_path('app/mysql/'));
                $zip->close();
            } else {
                throw new Exception('Extract failed');
            }
            // get file from s3
            $s3 = \Storage::disk();
            $found = $s3->get('/mysql/' . $snapshot . '.sql');
            $tempLocation = '/tmp/' . env('DB_DATABASE') . '_' . date('Y-m-d_Hi') . '.sql';

            // create a temp file
            $bytes_written = File::put($tempLocation, $found);
            if ($bytes_written === false) {
                $this->info('Error writing to file: ' . $tempLocation);
            }

            // run the cli job
            $process = new Process(
                'mysql -h ' .
                    env('DB_HOST') .
                    ' -u ' .
                    env('DB_USERNAME') .
                    ' -p' .
                    env('DB_PASSWORD') .
                    ' ' .
                    env('DB_DATABASE') .
                    " < {$tempLocation}",
            );
            $process->run();

            @unlink($tempLocation);
            @unlink(storage_path('app/mysql/' . $snapshot . '.sql'));
            if ($process->isSuccessful()) {
                $this->info('Restored snapshot: ' . $snapshot);
            } else {
                throw new ProcessFailedException($process);
            }
        } catch (\Exception $e) {
            $this->info('File Not Found: ' . $e->getMessage(), $e->getFile(), $e->getLine());
        }
    }
}
