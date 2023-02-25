<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Routing\Router;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$route = new Router();
$route->run();