<?php

namespace Fetcher;

use Assert\Assertion;

class Url
{
    private $url;

    public function __construct($url)
    {
        Assertion::url($url);

        $this->url = $url;
    }

    public function isImage()
    {
        return preg_match('/\.jpg$/', $this->url);
    }

    public function __toString()
    {
        return $this->url;
    }
}
