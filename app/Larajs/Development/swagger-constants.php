<?php
require_once "vendor/autoload.php";
$rootPath = __DIR__ . '/../../../';
$dotenv = \Dotenv\Dotenv::create($rootPath); // Dir where your .env file is located
$dotenv->load();
$url = $_ENV['APP_URL'];
define("API_HOST_V1", "$url/api/v1");
define("API_HOST_V2", "$url/api/v2");
