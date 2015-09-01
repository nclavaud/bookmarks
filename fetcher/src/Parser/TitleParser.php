<?php

namespace Fetcher\Parser;

use Fetcher\DataParser;
use Symfony\Component\DomCrawler\Crawler;

class TitleParser implements DataParser
{
    public function find(Crawler $crawler)
    {
        try {
            return trim($crawler->filterXPath('//head/title')->text());
        } catch (\InvalidArgumentException $e) {
            return null;
        }
    }
}
