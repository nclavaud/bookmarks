<?php

namespace Fetcher\Parser;

use Fetcher\DataParser;
use Symfony\Component\DomCrawler\Crawler;

class ChainParser implements DataParser
{
    private $parsers;

    public function __construct(array $parsers)
    {
        \Assert\Assertion::allIsInstanceOf($parsers, '\Fetcher\DataParser');

        $this->parsers = $parsers;
    }

    public function find(Crawler $crawler)
    {
        foreach ($this->parsers as $parser) {
            $value = $parser->find($crawler);
            if (null !== $value) {
                return $value;
            }
        }

        return null;
    }
}
