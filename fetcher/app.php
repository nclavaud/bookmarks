<?php

require 'vendor/autoload.php';

use Fetcher\Fetcher;
use Fetcher\Url;

$url = $argv[1];

$fetcher = new Fetcher();
$resource = $fetcher->fetch(new Url($url));

echo sprintf('%s: %s%s', 'UUID', $resource->getUuid(), PHP_EOL);
echo sprintf('%s: %s%s', 'URL', $resource->getUrl(), PHP_EOL);
echo sprintf('%s: %s%s', 'Title', $resource->getTitle(), PHP_EOL);
