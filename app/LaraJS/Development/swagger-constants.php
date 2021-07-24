<?php
require_once 'vendor/autoload.php';
$rootPath = __DIR__ . '/../../../';
$dotenv = \Dotenv\Dotenv::createMutable($rootPath); // Dir where your .env file is located
$dotenv->load();
$url = $_ENV['APP_URL'];
define('API_HOST', "$url/api");
define('API_HOST_V1', "$url/api/v1");
