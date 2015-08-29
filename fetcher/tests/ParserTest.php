<?php

namespace Fetcher\Tests;

use Fetcher\Parser;

class ParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_can_parse_a_web_page()
    {
        $content = <<<'HTML'
<html>
    <head>
        <title>My blog</title>
        <meta property="og:image" content="http://example.org/image.jpg" />
    </head>
    <body>
        <h1>A new post</h1>
        <p>Welcome on my blog</p>
    </body>
</html>
HTML;

        $parsedData = (new Parser())->parse($content);

        $this->assertEquals('My blog', $parsedData->title);
        $this->assertEquals('http://example.org/image.jpg', $parsedData->imageUrl);
    }

    /**
     * @test
     */
    public function it_can_parse_an_empty_web_page()
    {
        $content = <<<'HTML'
<html>
</html>
HTML;

        $parsedData = (new Parser())->parse($content);

        $this->assertEquals(null, $parsedData->title);
        $this->assertEquals(null, $parsedData->imageUrl);
    }

    /**
     * @test
     */
    public function it_will_use_first_image_tag_if_open_graph_image_is_missing()
    {
        $content = <<<'HTML'
<html>
    <body>
        <img src="http://example.org/image-tag.jpg" />
    </body>
</html>
HTML;

        $parsedData = (new Parser())->parse($content);

        $this->assertEquals('http://example.org/image-tag.jpg', $parsedData->imageUrl);
    }
}
