<?php

require 'vendor/autoload.php';

use Fetcher\Fetcher;
use Fetcher\Url;
use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = false;

while (false === $connection) {
    try {
        $connection = new AMQPStreamConnection(
            getenv('RABBITMQ_HOST'),
            getenv('RABBITMQ_PORT'),
            getenv('RABBITMQ_USER'),
            getenv('RABBITMQ_PASSWORD')
        );
    } catch (\Exception $e) {
        // @see http://brunorocha.org/python/dealing-with-linked-containers-dependency-in-docker-compose.html
        // for a better solution
        sleep(1);
    }
}
$channel = $connection->channel();

$channel->queue_declare('bookmark', false, false, false, false);

echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

$fetcher = new Fetcher();
$callback = function ($msg) use ($fetcher) {
    echo " [x] Received ", $msg->body, "\n";

    $message = json_decode($msg->body);
    $url = $message->url;

    $resource = $fetcher->fetch(new Url($url));
    echo sprintf('%s: %s%s', 'UUID', $resource->getUuid(), PHP_EOL);
    echo sprintf('%s: %s%s', 'URL', $resource->getUrl(), PHP_EOL);
    echo sprintf('%s: %s%s', 'Title', $resource->getTitle(), PHP_EOL);
    echo sprintf('%s: %s%s', 'Image URL', $resource->getImageUrl(), PHP_EOL);
};

$channel->basic_consume('bookmark', '', false, true, false, false, $callback);

//while (count($channel->callbacks)) {
while (true) {
    $channel->wait();
}
