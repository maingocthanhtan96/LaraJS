<?php

namespace App\Console\Commands;

use App\Services\FileService;
use App\Services\GeneratorService;
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

    /** @var GeneratorService $service */
    public GeneratorService $serviceGenerator;

    /** @var FileService $service */
    public FileService $serviceFile;

    /** @var string */
    public string $basePath;

    /** @var string */
    public string $env;

    /** @var string */
    public string $appUrlStub;

    /** @var string */
    public string $dbHostStub;

    /** @var string */
    public string $dbPortStub;

    /** @var string */
    public string $dbDatabaseStub;

    /** @var string */
    public string $dbUsernameStub;

    /** @var string */
    public string $dbPasswordStub;

    /** @var string */
    public string $appUrl;

    /** @var string */
    public string $host;

    /** @var string */
    public string $port;

    /** @var string */
    public string $database;

    /** @var string */
    public string $username;

    /** @var string */
    public string $password;

    /** @var string */
    public string $cacheConfig;

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
        try {
            list($fileEnvEx, $fileConfig) = $this->_createEnv();
            $this->_installMigrateSeed();
            $this->_installPackage();
            $this->_generateFile();
            $this->_installPassport($fileEnvEx, $fileConfig);
            $this->_deployVue();
            $this->_deploySwagger();
            $this->_deployStorage();

            $this->_outputArtisan('config:clear');
            $this->info($this->_textSignature());
            $this->info('By: Mai Ngọc Thanh Tân');
            $this->comment('SETUP SUCCESSFULLY!');
        } catch (\Exception $e) {
            $this->error($e->getMessage());
            $this->info('>>> Running: Remove env');
            $this->_outputArtisan('config:clear');
            File::delete(base_path($this->env));
            $this->comment('==========Stop setup==========');
        }
    }

    private function _installMigrateSeed()
    {
        $this->info('>>> Running: migrate and seed');
        $this->_outputArtisan('migrate:fresh --seed');
    }

    private function _installPackage()
    {
        $this->comment('INSTALL PACKAGE');
        $this->info('>>> Running: npm install backend');
        exec('npm install');
        exec('npm rebuild node-sass');
        $this->info('>>> Running: npm install frontend');
        exec('cd frontend && npm install');
    }

    private function _generateFile()
    {
        $this->comment('GENERATE KEY');
        $this->_outputArtisan('key:generate');
        $this->comment('GENERATE LANG');
        $this->_outputArtisan('vue-i18n:generate');
        $this->info('Generate lang successfully.');
        $this->comment('GENERATE IDE');
        $this->_outputArtisan('ide-helper:generate');
    }

    private function _installPassport($fileEnvEx, $fileConfig)
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
            $this->_createEnvWithPassport($fileEnvEx, $fileConfig, reset($secret));
        }
    }

    private function _deployVue()
    {
        $this->comment('DEPLOY VUE');
        $this->info('>>> Running: deploy backend');
        exec('npm run dev');
        $this->info('>>> Running: deploy frontend');
        exec('npm run dev-fe');
    }

    private function _deploySwagger()
    {
        $this->comment('DEPLOY SWAGGER');
        $this->info('>>> Running: deploy swagger');
        exec('./swagger.sh');
    }

    private function _deployStorage()
    {
        $this->comment('DEPLOY Storage');
        $this->info('>>> Running: deploy storage:link');
        exec('php artisan storage:link');
    }

    private function _createEnv(): array
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
        $parPassword = '';
        $this->info('>>> Running: create env');
        $this->appUrl = $this->anticipate('What is your url?', [$parAppUrl], $parAppUrl);
        $this->host = $this->anticipate('What is your host?', [$parHost], $parHost);
        $this->port = $this->anticipate('What is your port?', [$parPort], $parPort);
        $this->database = $this->anticipate('What is your database?', [$parDatabase], $parDatabase);
        $this->username = $this->anticipate('What is your username?', [$parUsername], $parUsername);
        $this->password = $this->anticipate('What is your password?', [$parPassword], $parPassword);

        $fileEnvEx = File::get(base_path($envExample));
        $fileEnvEx = $this->_replaceEnvConfig($fileEnvEx);

        $fileConfig = File::get($this->cacheConfig);
        $fileConfig = $this->_replaceEnvConfig($fileConfig);

        File::put(base_path($this->env), $fileEnvEx);
        File::put($this->cacheConfig, $fileConfig);

        $this->_outputArtisan('config:cache');

        return [$fileEnvEx, $fileConfig];
    }

    private function _replaceEnvConfig($fileEnvEx)
    {
        $fileEnvEx = str_replace($this->appUrlStub, $this->appUrl, $fileEnvEx);
        $fileEnvEx = str_replace($this->dbHostStub, $this->host, $fileEnvEx);
        $fileEnvEx = str_replace($this->dbPortStub, $this->port, $fileEnvEx);
        $fileEnvEx = str_replace($this->dbDatabaseStub, $this->database, $fileEnvEx);
        $fileEnvEx = str_replace($this->dbUsernameStub, $this->username, $fileEnvEx);
        $fileEnvEx = str_replace($this->dbPasswordStub, $this->password, $fileEnvEx);

        return $fileEnvEx;
    }

    private function _createEnvWithPassport($fileEnvEx, $fileConfig, $secret)
    {
        $passport = '{{PASSPORT_CLIENT_SECRET}}';
        $fileEnvEx = str_replace($passport, $secret, $fileEnvEx);
        $fileConfig = str_replace($passport, $secret, $fileConfig);

        File::put(base_path($this->env), $fileEnvEx);
        File::put($this->cacheConfig, $fileConfig);

        $this->_outputArtisan('config:cache');
    }

    private function _outputArtisan($command, $params = [])
    {
        Artisan::call($command, $params, $this->getOutput());
    }

    private function _textSignature(): string
    {
        // ANSI Shadow
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
