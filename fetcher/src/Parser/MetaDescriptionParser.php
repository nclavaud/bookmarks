<?php

namespace Fetcher\Parser;

use Fetcher\DataParser;
use Symfony\Component\DomCrawler\Crawler;

class MetaDescriptionParser implements DataParser
{
    public function find(Crawler $crawler)
    {
        try {
            return $crawler->filterXPath('//meta[@name="description"]')->attr('content');
        } catch (\InvalidArgumentException $e) {
            return null;
        }
    }
}
