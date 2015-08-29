<?php

namespace Fetcher;

use Rhumsaa\Uuid\Uuid;

class Fetcher
{
    public function fetch(Url $url)
    {
        $browser = new \Buzz\Browser(new \Buzz\Client\Curl());
        $response = $browser->get((string) $url);

        $parsedData = (new Parser())->parse($response->getContent());

        return new Resource(
            Uuid::fromString(Uuid::NIL),
            $url,
            $parsedData->title,
            new Url($parsedData->imageUrl)
        );
    }
}
