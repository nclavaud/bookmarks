<?php

namespace App;

use Assert\Assertion;

class Url
{
    private $url;

    public function __construct($url)
    {
        Assertion::url($url);

        $this->url = $url;
    }

    public function __toString()
    {
        return $this->url;
    }
}
