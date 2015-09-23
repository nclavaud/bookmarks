<?php

namespace Fetcher;

use Symfony\Component\DomCrawler\Crawler;

class Parser
{
    public function parse($content, Url $url)
    {
        $data = (object) array();

        $crawler = new Crawler($content);

        $data->title = (new Parser\TitleParser())->find($crawler);

        $imageParser = new Parser\ChainParser(
            array(
                new Parser\OpenGraphImageParser(),
                new Parser\ImageTagParser(),
            )
        );
        $data->imageUrl = $imageParser->find($crawler);
        if (null !== $data->imageUrl) {
            $data->imageUrl = (new Uri($imageParser->find($crawler)))->toAbsoluteUrl($url);
        }

        $data->videoUrl = (new Parser\OpenGraphVideoParser())->find($crawler);

        $descriptionParser = new Parser\ChainParser(
            array(
                new Parser\OpenGraphDescriptionParser(),
                new Parser\MetaDescriptionParser(),
            )
        );
        $data->description = $descriptionParser->find($crawler);

        $data->type = (new Parser\OpenGraphTypeParser())->find($crawler);
        if (null === $data->type) {
            $data->type = 'unknown';
        }

        return $data;
    }
}
