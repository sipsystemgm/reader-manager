<?php

namespace Sip\ReaderManager\Test;

use Sip\ImageParser\Interfaces\ImageParserInterface;
use Sip\ReaderManager\Factory\HtmlReaderSymfonyCrawlerParserFactory;
use PHPUnit\Framework\TestCase;

class HtmlReaderSymfonyCrawlerParserFactoryTest extends TestCase
{
    public function testCreateParser()
    {
        $readerParser = new HtmlReaderSymfonyCrawlerParserFactory('https://google.com');
        $this->assertInstanceOf(ImageParserInterface::class, $readerParser->createParser());
    }

    public function testCreateParserWrongHost()
    {
        $wrongUrl = '/some-page.html';
        $readerParser = new HtmlReaderSymfonyCrawlerParserFactory($wrongUrl);
        try {
            $this->assertInstanceOf(ImageParserInterface::class, $readerParser->createParser());
        } catch (\Throwable $exception) {
            $this->assertEquals(
                sprintf(HtmlReaderSymfonyCrawlerParserFactory::ERROR_HOST_MESSAGE, $wrongUrl),
                $exception->getMessage()
            );
        }
    }

    public function testCreateReader()
    {
        $readerParser = new HtmlReaderSymfonyCrawlerParserFactory('https://google.com');
        $this->assertInstanceOf(\Iterator::class, $readerParser->createReader());
    }
}

