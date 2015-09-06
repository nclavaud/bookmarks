<?php

namespace Fetcher;

class Fetcher
{
    public function fetch(Url $url)
    {
        if ($url->isImage()) {
            return new Resource(
                $url,
                '',
                '',
                $url,
                null,
                'image'
            );
        }

        $browser = new \Buzz\Browser(new \Buzz\Client\Curl());
        $response = $browser->get((string) $url);

        $parsedData = (new Parser())->parse($response->getContent());

        return new Resource(
            $url,
            $parsedData->title,
            $parsedData->description,
            (null !== $parsedData->imageUrl) ? new Url($parsedData->imageUrl) : null,
            (null !== $parsedData->videoUrl) ? new Url($parsedData->videoUrl) : null,
            $parsedData->type
        );
    }
}
