<?php

namespace Fetcher;

use Symfony\Component\DomCrawler\Crawler;

class Parser
{
    public function parse($content)
    {
        $data = (object) array();

        $crawler = new Crawler($content);

        try {
            $data->title = $crawler->filterXPath('//head/title')->text();
        } catch (\InvalidArgumentException $e) {
            $data->title = null;
        }

        try {
            $data->imageUrl = $crawler->filterXPath('//meta[@property="og:image"]')->attr('content');
        } catch (\InvalidArgumentException $e) {
            try {
                $data->imageUrl = $crawler->filterXPath('//img')->attr('src');
            } catch (\InvalidArgumentException $e) {
                $data->imageUrl = null;
            }
        }

        return $data;
    }
}
