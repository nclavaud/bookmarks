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
    echo sprintf('%s: %s%s', 'URL', $resource->getUrl(), PHP_EOL);
    echo sprintf('%s: %s%s', 'Type', $resource->getType(), PHP_EOL);
    echo sprintf('%s: %s%s', 'Title', $resource->getTitle(), PHP_EOL);
    echo sprintf('%s: %s%s', 'Description', $resource->getDescription(), PHP_EOL);
    echo sprintf('%s: %s%s', 'Image URL', $resource->getImageUrl(), PHP_EOL);
    echo sprintf('%s: %s%s', 'Video URL', $resource->getVideoUrl(), PHP_EOL);

    // call API
    $url = sprintf('http://api:8080/%s', $message->uuid);
    $headers = array(
        'Content-Type' => 'application/json',
    );
    $payload = json_encode((object) array(
        'type' => $resource->getType(),
        'title' => $resource->getTitle(),
        'description' => $resource->getDescription(),
        'imageUrl' => (null !== $resource->getImageUrl()) ? (string) $resource->getImageUrl() : null,
        'videoUrl' => (null !== $resource->getVideoUrl()) ? (string) $resource->getVideoUrl() : null,
    ));
    $browser = new \Buzz\Browser(new \Buzz\Client\Curl());
    $browser->post($url, $headers, $payload);
};

$channel->basic_consume('bookmark', '', false, true, false, false, $callback);

while (true) {
    $channel->wait();
}
