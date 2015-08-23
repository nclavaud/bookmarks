<?php

require_once __DIR__ . '/../vendor/autoload.php';

use fiunchinho\Silex\Provider\RabbitServiceProvider;
use Rhumsaa\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app = new Silex\Application();

$app->register(new RabbitServiceProvider(), array(
    'rabbit.connections' => array(
        'default' => array(
            'host' => 'rabbitmq',
            'port' => 5672,
            'user' => 'guest',
            'password' => 'guest',
            'vhost' => '/',
        ),
    ),
    'rabbit.producers' => array(
        'bookmark' => array(
            'connection' => 'default',
            'exchange_options' => array(
                'name' => 'bookmark_exchange',
                'type' => 'topic',
            ),
        ),
    ),
));

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

$app->post('/', function (Request $request) use ($app) {
    $bookmark = new App\Bookmark(Uuid::uuid4(), new App\Url($request->request->get('url')));

    $app['bookmark.repository']->save($bookmark);

    $message = json_encode((object) array(
        'event' => 'bookmark_has_been_created',
        'uuid' => (string) $bookmark->getUuid(),
    ));
    $app['rabbit.producer']['bookmark']->publish($message);

    return $app->json($bookmark, 201);
});

$app->run();
