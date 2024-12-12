<?php
session_start();
require_once '../vendor/autoload.php';
require_once '../src/routes.php';
require_once '../rest/functions/functions.php';

$router->run($router->routes);