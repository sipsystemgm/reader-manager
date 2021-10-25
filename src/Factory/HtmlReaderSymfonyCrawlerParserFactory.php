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
        return new ReadFromFile($this->url, 10);
    }

    public function createParser(bool $isHrefSubDomain = false, bool $isImgSubDomain = false): ImageParserInterface
    {
        $parsedUrl = parse_url($this->url);
        if (empty($parsedUrl['host'])) {
            throw new \Exception(sprintf(self::ERROR_HOST_MESSAGE, $this->url));
        }
        $hrefValidator = new TagUrlValidator($parsedUrl['host']);
        $hrefValidator->setIsValidSubdomain($isHrefSubDomain);

        $srcValidator = new TagUrlValidator($parsedUrl['host']);
        $srcValidator->setIsValidSubdomain($isImgSubDomain);

        $parser = new SymfonyCrawlerParser();
        $parser->addTagValidators('href', $hrefValidator);
        $parser->addTagValidators('src', $srcValidator);
        return $parser;
    }
}
