<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Rhumsaa\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$app = new Silex\Application();

$app['debug'] = getenv('DEBUG');

$app['bookmark.repository'] = function () {
    return new App\BookmarkRepository(new \PDO(getenv('DSN')));
};

$app->before(function (Request $request) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});

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
        'url' => (string) $bookmark->getUrl(),
    ));

    // publish to RabbitMQ
    $connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
    $channel = $connection->channel();
    $channel->queue_declare('bookmark', false, false, false, false);
    $msg = new AMQPMessage($message);
    $channel->basic_publish($msg, '', 'bookmark');
    $channel->close();
    $connection->close();

    return $app->json($bookmark, 201);
});

// @todo restrict to internal calls only
$app->post('/{uuid}', function (Request $request, $uuid) use ($app) {
    $bookmark = $app['bookmark.repository']->find(Uuid::fromString($uuid));

    $bookmark->complete(
        $request->request->get('type'),
        $request->request->get('title'),
        new App\Url($request->request->get('imageUrl'))
    );

    $app['bookmark.repository']->update($bookmark);

    return $app->json($bookmark, 200);
});

$app->post('/{uuid}/delete', function (Request $request, $uuid) use ($app) {
    $bookmark = $app['bookmark.repository']->remove(Uuid::fromString($uuid));

    return $app->json($bookmark, 200);
});

$app->run();
