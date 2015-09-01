<?php

namespace Fetcher;

use Rhumsaa\Uuid\Uuid;

class Fetcher
{
    public function fetch(Url $url)
    {
        if ($url->isImage()) {
            return new Resource(
                Uuid::fromString(Uuid::NIL),
                $url,
                '',
                $url,
                'image'
            );
        }

        $browser = new \Buzz\Browser(new \Buzz\Client\Curl());
        $response = $browser->get((string) $url);

        $parsedData = (new Parser())->parse($response->getContent());

        return new Resource(
            Uuid::fromString(Uuid::NIL),
            $url,
            $parsedData->title,
            (null !== $parsedData->imageUrl) ? new Url($parsedData->imageUrl) : null
        );
    }
}
