<?php

namespace Fetcher;

use Rhumsaa\Uuid\Uuid;
use Symfony\Component\DomCrawler\Crawler;

class Fetcher
{
    public function fetch(Url $url)
    {
        $browser = new \Buzz\Browser(new \Buzz\Client\Curl());
        $response = $browser->get((string) $url);
        $crawler = new Crawler($response->getContent());

        $title = $crawler->filterXPath('//head/title')->text();
        $imageUrl = $crawler->filterXPath('//meta[@property="og:image"]')->attr('content');

        return new Resource(
            Uuid::fromString(Uuid::NIL),
            $url,
            $title,
            new Url($imageUrl)
        );
    }
}
