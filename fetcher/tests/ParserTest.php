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
}
