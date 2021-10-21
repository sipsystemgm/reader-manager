<?php

namespace Sip\ReaderManager\Factory;

use Sip\ImageParser\Interfaces\ImageParserInterface;
use Sip\ImageParser\SymfonyCrawlerParser;
use Sip\ImageParser\TagUrlValidator;
use Sip\ReaderManager\Interfaces\AbstractFactoryInterface;
use Sip\Reader\ReadFromFile;

class HtmlReaderSymfonyCrawlerParserFactory implements AbstractFactoryInterface
{
    public const ERROR_HOST_MESSAGE = "Can't find host in url [%s]";
    private string $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    public function createReader(): \Iterator
    {
        $reader = new ReadFromFile($this->url, 4069);
        return $reader;
    }

    public function createParser(): ImageParserInterface
    {
        $parsedUrl = parse_url($this->url);

        if (empty($parsedUrl['host'])) {
            throw new \Exception(sprintf(self::ERROR_HOST_MESSAGE, $this->url));
        }

        $validator = new TagUrlValidator($parsedUrl['host']);
        $parser = new SymfonyCrawlerParser();

        $parser->addTagValidators('src', $validator);
        $parser->addTagValidators('href', $validator);

        return $parser;
    }
}
