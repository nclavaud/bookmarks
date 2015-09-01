<?php

namespace Fetcher\Parser;

use Fetcher\DataParser;
use Symfony\Component\DomCrawler\Crawler;

class OpenGraphDescriptionParser implements DataParser
{
    public function find(Crawler $crawler)
    {
        try {
            $description = $crawler->filterXPath('//meta[@property="og:description"]')->attr('content');

            return empty($description) ? null : $description;
        } catch (\InvalidArgumentException $e) {
            return null;
        }
    }
}
