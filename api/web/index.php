<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app = new Silex\Application();

$app['debug'] = getenv('DEBUG');

$app['bookmark.repository'] = function () {
    return new App\BookmarkRepository(new \PDO(getenv('DSN')));
};

$app->after(function (Request $request, Response $response) {
    $response->headers->set('Access-Control-Allow-Origin', '*');
});

$app->get('/', function () use ($app) {
    $resources = $app['bookmark.repository']->findAll();

    return json_encode($resources);
});

$app->run();
