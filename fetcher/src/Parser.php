<?php

namespace Fetcher;

use Symfony\Component\DomCrawler\Crawler;

class Parser
{
    public function parse($content)
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
