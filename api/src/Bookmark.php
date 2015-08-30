<?php

namespace App;

use Rhumsaa\Uuid\Uuid;

class Bookmark implements \JsonSerializable
{
    private $uuid;
    private $url;
    private $data;

    public function __construct(Uuid $uuid, Url $url)
    {
        $this->uuid = $uuid;
        $this->url = $url;
        $this->data = array(
            'type' => 'unknown',
        );
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

    public function updateTitle($title)
    {
        $this->data['title'] = $title;

        return $this;
    }

    public function updateImageUrl(Url $imageUrl)
    {
        $this->data['image'] = (string) $imageUrl;

        return $this;
    }

    public function unserialize(array $data)
    {
        return new self(
            Uuid::fromString($data['uuid']),
            new Url($data['url'])
        );
    }

    public function jsonSerialize()
    {
        return array(
            'uuid' => (string) $this->uuid,
            'url' => (string) $this->url,
            'title' => (string) $this->url,
            'type' => $this->data['type'],
            'image' => null,
        );
    }
}
