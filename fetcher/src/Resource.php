<?php

namespace Fetcher;

use Rhumsaa\Uuid\Uuid;

class Resource
{
    private $uuid;
    private $url;
    private $title;

    public function __construct(Uuid $uuid, Url $url, $title)
    {
        $this->uuid = $uuid;
        $this->url = $url;
        $this->title = $title;
    }

    public function getUuid()
    {
        return $this->uuid;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function __toString()
    {
        return sprintf('%s %s %s', $this->uuid, $this->url, $this->title);
    }
}
