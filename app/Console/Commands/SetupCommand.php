<?php

namespace App\Console\Commands;

use App\Service\FileService;
use App\Service\GeneratorService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class SetupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'larajs:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup LaraJS';

    /** @var $service */
    public $serviceGenerator;

    /** @var $service */
    public $serviceFile;

    /** @var string */
    public $basePath;

    /** @var string */
    public $env;

    /** @var string */
    public $appUrlStub;

    /** @var string */
    public $dbHostStub;

    /** @var string */
    public $dbPortStub;

    /** @var string */
    public $dbDatabaseStub;

    /** @var string */
    public $dbUsernameStub;

    /** @var string */
    public $dbPasswordStub;

    /** @var string */
    public $appUrl;

    /** @var string */
    public $host;

    /** @var string */
    public $port;

    /** @var string */
    public $database;

    /** @var string */
    public $username;

    /** @var string */
    public $password;

    /** @var string */
    public $cacheConfig;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->serviceGenerator = new GeneratorService();
        $this->serviceFile = new FileService();
        $this->basePath = base_path();
        $this->env = '.env';
        $this->cacheConfig = base_path('bootstrap/cache/config.php');
    }

    /**
     * Execute the console command.
     *
     * @return string
     */
    public function handle()
    {
            list($fileEnvEx, $fileConfig) = $this->__createEnv();
            $this->__installMigrateSeed();
            $this->__installPackage();
            $this->__generateFile();
            $this->__installPassport($fileEnvEx, $fileConfig);
            $this->__deployVue();

            Artisan::call('config:clear', [], $this->getOutput());
            $this->info($this->__textSignature());
            $this->comment('SETUP SUCCESSFULLY!');
    }

    private function __installMigrateSeed()
    {
        $this->info('>>> Running: migrate and seed');
        Artisan::call('migrate:fresh --seed', [], $this->getOutput());
    }

    private function __installPackage()
    {
        $this->comment('INSTALL PACKAGE');
        $this->info('>>> Running: npm install');
        exec('npm install');
        exec('npm rebuild node-sass');
    }

    private function __generateFile()
    {
        $this->comment('GENERATE KEY');
        Artisan::call('key:generate', [], $this->getOutput());
        $this->comment('GENERATE LANG');
        Artisan::call('vue-i18n:generate', [], $this->getOutput());
        $this->info('Generate lang successfully.');
        $this->comment('GENERATE IDE');
        Artisan::call('ide-helper:generate', [], $this->getOutput());
    }

    private function __installPassport($fileEnvEx, $fileConfig)
    {
        $this->comment('INSTALL PASSPORT');
        $this->info('>>> Running: install passport');
        Artisan::call('passport:install');
        $passport = Artisan::output();
        $this->comment('KEY PASSPORT');
        $this->info($passport);
        preg_match_all('/Client secret:\s\w+/mi', $passport, $match);
        $match = reset($match);
        if ($match) {
            preg_match('/\w+$/mi', $match[1], $secret);
            $this->__createEnvWithPassport($fileEnvEx, $fileConfig, reset($secret));
        }
    }

    private function __deployVue()
    {
        $this->comment('DEPLOY VUE');
        $this->info('>>> Running: deploy vue');
        exec('npm run dev');
    }

    private function __createEnv()
    {
        $this->comment('SETUP DATABASE');
        $this->appUrlStub = '{{APP_URL}}';
        $this->dbHostStub = '{{DB_HOST}}';
        $this->dbPortStub = '{{DB_PORT}}';
        $this->dbDatabaseStub = '{{DB_DATABASE}}';
        $this->dbUsernameStub = '{{DB_USERNAME}}';
        $this->dbPasswordStub = '{{DB_PASSWORD}}';
        $envExample = '.env.example';
        $parAppUrl = 'http://local.larajs.com';
        $parHost = '127.0.0.1';
        $parPort = '3306';
        $parDatabase = 'larajs';
        $parUsername = 'root';
        $parPassword = 'root';
        $this->info('>>> Running: create env');
        $this->appUrl = $this->anticipate('What is your url?', [$parAppUrl], $parAppUrl);
        $this->host = $this->anticipate('What is your host?', [$parHost], $parHost);
        $this->port = $this->anticipate('What is your port?', [$parPort], $parPort);
        $this->database = $this->anticipate('What is your database?', [$parDatabase], $parDatabase);
        $this->username = $this->anticipate('What is your username?', [$parUsername], $parUsername);
        $this->password = $this->anticipate('What is your password?', [$parPassword], $parPassword);

        $fileEnvEx = File::get(base_path($envExample));
        $fileEnvEx = $this->__replaceEnvConfig($fileEnvEx);

        $fileConfig = File::get($this->cacheConfig);
        $fileConfig = $this->__replaceEnvConfig($fileConfig);

        File::put(base_path($this->env), $fileEnvEx);
        File::put($this->cacheConfig, $fileConfig);

        Artisan::call('config:cache');

        return [$fileEnvEx, $fileConfig];
    }

    private function __replaceEnvConfig($fileEnvEx)
    {
        $fileEnvEx = str_replace($this->appUrlStub, $this->appUrl, $fileEnvEx);
        $fileEnvEx = str_replace($this->dbHostStub, $this->host, $fileEnvEx);
        $fileEnvEx = str_replace($this->dbPortStub, $this->port, $fileEnvEx);
        $fileEnvEx = str_replace($this->dbDatabaseStub, $this->database, $fileEnvEx);
        $fileEnvEx = str_replace($this->dbUsernameStub, $this->username, $fileEnvEx);
        $fileEnvEx = str_replace($this->dbPasswordStub, $this->password, $fileEnvEx);

        return $fileEnvEx;
    }

    private function __createEnvWithPassport($fileEnvEx, $fileConfig, $secret)
    {
        $passport = '{{PASSPORT_CLIENT_SECRET}}';
        $fileEnvEx = str_replace($passport, $secret, $fileEnvEx);
        $fileConfig = str_replace($passport, $secret, $fileConfig);

        File::put(base_path($this->env), $fileEnvEx);
        File::put($this->cacheConfig, $fileConfig);

        Artisan::call('config:cache');
    }

    private function __textSignature()
    {
        return <<<SIGNATURE
██╗      █████╗ ██████╗  █████╗      ██╗███████╗
██║     ██╔══██╗██╔══██╗██╔══██╗     ██║██╔════╝
██║     ███████║██████╔╝███████║     ██║███████╗
██║     ██╔══██║██╔══██╗██╔══██║██   ██║╚════██║
███████╗██║  ██║██║  ██║██║  ██║╚█████╔╝███████║
╚══════╝╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝ ╚════╝ ╚══════╝
SIGNATURE;
    }
}
