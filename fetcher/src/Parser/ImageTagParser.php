<?php

namespace Fetcher\Parser;

use Fetcher\DataParser;
use Symfony\Component\DomCrawler\Crawler;

class ImageTagParser implements DataParser
{
    public function find(Crawler $crawler)
    {
        try {
            return $crawler->filterXPath('//img[starts-with(@src, \'http\')]')->attr('src');
        } catch (\InvalidArgumentException $e) {
            return null;
        }
    }
}
