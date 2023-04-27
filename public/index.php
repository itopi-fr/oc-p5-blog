<?php

use App\Sys\InitApp;

const ROOT_DIR = __DIR__ . '/../';
require_once ROOT_DIR . 'vendor/autoload.php';

session_start();

// Init
(new InitApp())->init();
