<?php

namespace Fetcher\Tests;

use Fetcher\Uri;

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

    /**
     * @test
     * @dataProvider lastDirProvider
     */
    public function it_can_return_last_dir($uri, $expected)
    {
        $this->assertEquals($expected, (new Uri($uri))->getLastDir());
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
}
