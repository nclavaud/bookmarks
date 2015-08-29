<?php

namespace Fetcher;

use Rhumsaa\Uuid\Uuid;

class Resource
{
    private $uuid;
    private $url;
    private $title;
    private $imageUrl;

    public function __construct(Uuid $uuid, Url $url, $title, Url $imageUrl = null)
    {
        $this->uuid = $uuid;
        $this->url = $url;
        $this->title = $title;
        $this->imageUrl = $imageUrl;
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

    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    public function __toString()
    {
        return sprintf('%s %s %s', $this->uuid, $this->url, $this->title);
    }
}
