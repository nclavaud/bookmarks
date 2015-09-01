<?php

namespace Fetcher;

use Symfony\Component\DomCrawler\Crawler;

interface DataParser
{
    public function find(Crawler $crawler);
}
