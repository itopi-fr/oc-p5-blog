<?php
const ROOT_DIR = __DIR__ . '/../';
require ROOT_DIR . 'vendor/autoload.php';


use App\Routing\Router;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(ROOT_DIR);
$dotenv->load();

$route = new Router();
$route->run();