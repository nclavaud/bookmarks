<?php

namespace Fetcher\Tests;

use Fetcher\Uri;
use Fetcher\Url;

class UriTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider rootDomainProvider
     */
    public function it_can_return_root_domain($uri, $expected)
    {
        $this->assertEquals($expected, (new Uri($uri))->getRootDomain());
    }

    public function rootDomainProvider()
    {
        return array(
            array('http://example.org/a/b/c.html', 'http://example.org/'),
            array('http://example.org/a/b.html', 'http://example.org/'),
            array('http://example.org/b.html', 'http://example.org/'),
            array('http://example.org/a/', 'http://example.org/'),
            array('http://example.org/', 'http://example.org/'),
            array('http://example.org', 'http://example.org/'),
        );
    }

    /**
     * @test
     * @dataProvider lastDirProvider
     */
    public function it_can_return_last_dir($uri, $expected)
    {
        $this->assertEquals($expected, (new Uri($uri))->getLastDir());
    }

    public function lastDirProvider()
    {
        return array(
            array('http://example.org/a/b/c.html', 'http://example.org/a/b/'),
            array('http://example.org/a/b.html', 'http://example.org/a/'),
            array('http://example.org/a/', 'http://example.org/a/'),
            array('http://example.org/', 'http://example.org/'),
            array('http://example.org', 'http://example.org/'),
        );
    }

    /**
     * @test
     * @dataProvider imageProvider
     */
    public function it_can_detect_image($uri, $expected)
    {
        $this->assertEquals($expected, (new Uri($uri))->isImage());
    }

    public function imageProvider()
    {
        return array(
            array('http://example.org/image.png', true),
            array('http://example.org/IMAGE.PNG', true),
            array('http://example.org/image.gif', true),
            array('http://example.org/image.jpg', true),
            array('http://example.org/image.jpeg', true),
            array('http://example.org/a.html', false),
        );
    }

    /**
     * @test
     * @dataProvider isAbsoluteProvider
     */
    public function it_can_detect_absolute_uris($uri, $expected)
    {
        $this->assertEquals($expected, (new Uri($uri))->isAbsolute());
    }

    public function isAbsoluteProvider()
    {
        return array(
            array('http://example.org/', true),
            array('https://example.org/', true),
            array('a/b/c.html', false),
        );
    }

    /**
     * @test
     * @dataProvider toAbsoluteUrlProvider
     */
    public function it_can_transform_a_relative_uri_into_an_absolute_url($url, $uri, $expected)
    {
        $this->assertEquals($expected, (string) (new Uri($uri))->toAbsoluteUrl(new Url($url)));
    }

    public function toAbsoluteUrlProvider()
    {
        return array(
            array('http://www.example.org/', 'a.html', 'http://www.example.org/a.html'),
            array('http://www.example.org/a/b.html', 'c.html', 'http://www.example.org/a/c.html'),
            array('http://www.example.org/a/b/', '/c.html', 'http://www.example.org/c.html'),
        );
    }
}
