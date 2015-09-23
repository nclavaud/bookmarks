<?php

namespace Fetcher;

use Assert\Assertion;

class Url extends Uri
{
    public function __construct($url)
    {
        Assertion::url($url);

        parent::__construct($url);
    }

}
