<?php

namespace Fetcher\Tests;

use Fetcher\Parser;
use Fetcher\Url;

class ParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_can_parse_a_web_page()
    {
        $url = new Url('http://example.org/'); 
        $content = <<<'HTML'
<html>
    <head>
        <title>My blog</title>
        <meta property="og:image" content="http://example.org/image.jpg" />
        <meta property="og:type" content="album" />
        <meta property="og:description" content="A fabulous album." />
        <meta property="og:video:url" content="https://example.org/video/">
    </head>
    <body>
        <h1>A new post</h1>
        <p>Welcome on my blog</p>
    </body>
</html>
HTML;

        $parsedData = (new Parser())->parse($content, $url);

        $this->assertEquals('My blog', $parsedData->title);
        $this->assertEquals('http://example.org/image.jpg', $parsedData->imageUrl);
        $this->assertEquals('album', $parsedData->type);
        $this->assertEquals('A fabulous album.', $parsedData->description);
        $this->assertEquals('https://example.org/video/', $parsedData->videoUrl);
    }

    /**
     * @test
     */
    public function it_can_parse_an_empty_web_page()
    {
        $url = new Url('http://example.org/'); 
        $content = <<<'HTML'
<html>
</html>
HTML;

        $parsedData = (new Parser())->parse($content, $url);

        $this->assertEquals(null, $parsedData->title);
        $this->assertEquals(null, $parsedData->imageUrl);
    }

    /**
     * @test
     */
    public function it_will_use_first_image_tag_if_open_graph_image_is_missing()
    {
        $url = new Url('http://example.org/'); 
        $content = <<<'HTML'
<html>
    <body>
        <img src="/image-tag-relative.jpg" />
        <img src="http://example.org/image-tag.jpg" />
    </body>
</html>
HTML;

        $parsedData = (new Parser())->parse($content, $url);

        $this->assertEquals('http://example.org/image-tag-relative.jpg', $parsedData->imageUrl);
    }
}
