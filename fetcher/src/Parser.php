<?php

namespace Fetcher;

use Symfony\Component\DomCrawler\Crawler;

class Parser
{
    public function parse($content)
    {
        $data = (object) array();

        $crawler = new Crawler($content);

        $data->title = $crawler->filterXPath('//head/title')->text();
        $data->imageUrl = $crawler->filterXPath('//meta[@property="og:image"]')->attr('content');

        return $data;
    }
}
