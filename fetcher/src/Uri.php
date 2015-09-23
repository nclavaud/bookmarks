<?php

namespace Fetcher;

class Uri
{
    private $uri;

    public function __construct($uri)
    {
        $this->uri = $uri;
    }

    public function toAbsoluteUrl(Url $url)
    {
        if ($this->isAbsolute()) {
            return $this;
        }

        if ($this->isRoot()) {
            return $url->getRootDomain() . mb_substr((string) $this->uri, 1);
        }

        return $url->getLastDir() . $this->uri;
    }

    public function isAbsolute()
    {
        return preg_match('/^http(s)?:\/\//', $this->uri);
    }

    public function isImage()
    {
        return preg_match('/\.(jpg|jpeg|png|gif)$/i', $this->uri);
    }

    public function isRoot()
    {
        return 0 === mb_strpos($this->uri, '/');
    }

    public function getRootDomain()
    {
        preg_match('/^http(?:s)?:\/\/([^\/]*)/', $this->uri, $matches);

        return $matches[0] . '/';
    }

    public function getLastDir()
    {
        preg_match('/^(http(?:s)?:\/\/.*\/)([^\/]*)$/', $this->uri, $matches);

        if (empty($matches)) {
            return $this->uri . '/';
        }

        return $matches[1];
    }

    public function __toString()
    {
        return $this->uri;
    }
}
