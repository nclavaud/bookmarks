<?php

namespace App;

use Rhumsaa\Uuid\Uuid;

class Bookmark
{
    private $uuid;
    private $url;
    private $data;

    public function __construct(Uuid $uuid, Url $url)
    {
        $this->uuid = $uuid;
        $this->url = $url;
        $this->data = array();
    }

    public function getUuid()
    {
        return $this->uuid;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getData()
    {
        return (object) $this->data;
    }
}
