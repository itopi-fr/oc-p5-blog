<?php
const ROOT_DIR = __DIR__ . '/../';
require ROOT_DIR . 'vendor/autoload.php';


use App\Routing\Router;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(ROOT_DIR);
$dotenv->load();

if ($_ENV['MODE_DEV'] === 'true') {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
}

session_start();

$route = new Router();
$route->run();
