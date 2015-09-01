<?php

namespace App;

use Rhumsaa\Uuid\Uuid;

class Bookmark implements \JsonSerializable
{
    const STATE_INCOMPLETE = 'incomplete';
    const STATE_COMPLETE = 'complete';

    private $uuid;
    private $url;
    private $data;

    public function __construct(Uuid $uuid, Url $url)
    {
        $this->uuid = $uuid;
        $this->url = $url;
        $this->data = array(
            'type' => 'unknown',
            'state' => self::STATE_INCOMPLETE,
        );
    }

    public function complete($type, $title, Url $imageUrl)
    {
        $this->data['type'] = $type;
        $this->data['title'] = $title;
        $this->data['image'] = (string) $imageUrl;
        $this->data['state'] = self::STATE_COMPLETE;

        return $this;
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
            'state' => $this->data['state'],
            'title' => (string) $this->url,
            'type' => $this->data['type'],
            'image' => null,
        );
    }
}
