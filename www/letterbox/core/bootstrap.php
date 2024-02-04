<?php

require __DIR__.'/../config.php';
require __DIR__ . '/DB.php';
require __DIR__.'/Router.php';
require __DIR__.'/../routes.php';


$router = new Router;
$router->setRoutes($routes);

$url = $_SERVER['REQUEST_URI'];
require __DIR__."/../api/".$router->getFilename($url);