<?php

namespace Fetcher;

class Resource
{
    private $url;
    private $title;
    private $description;
    private $imageUrl;
    private $type;

    public function __construct(Url $url, $title, $description, Url $imageUrl = null, $type)
    {
        $this->url = $url;
        $this->title = $title;
        $this->description = $description;
        $this->imageUrl = $imageUrl;
        $this->type = $type;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    public function getType()
    {
        return $this->type;
    }

    public function __toString()
    {
        return sprintf('%s %s', $this->url, $this->title);
    }
}
