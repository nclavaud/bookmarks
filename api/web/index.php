<?php

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();

$app['debug'] = getenv('DEBUG');

$app->get('/', function () use ($app) {
    $resources = array();

    return json_encode($resources);
});

$app->run();
