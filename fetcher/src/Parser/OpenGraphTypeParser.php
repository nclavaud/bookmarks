<?php

namespace Fetcher\Parser;

use Fetcher\DataParser;
use Symfony\Component\DomCrawler\Crawler;

class OpenGraphTypeParser implements DataParser
{
    public function find(Crawler $crawler)
    {
        try {
            return $crawler->filterXPath('//meta[@property="og:type"]')->attr('content');
        } catch (\InvalidArgumentException $e) {
            return null;
        }
    }
}
