<?php

namespace Sip\ReaderManager;

use Sip\ImageParser\Interfaces\ImageParserInterface;
use Sip\ImageParser\TagUrlValidator;
use Sip\ReaderManager\Interfaces\AbstractFactoryInterface;
use Sip\ReaderManager\Interfaces\ReaderManagerInterface;
use Sip\ReaderManager\Interfaces\ReaderStorageInterface;
use Sip\ReaderManager\Factory\HtmlReaderSymfonyCrawlerParserFactory;
use Sip\ReaderManager\Factory\ReaderParserFactory;

class ReaderManager implements ReaderManagerInterface
{
    private ReaderStorageInterface $readerStorage;
    private ImageParserInterface $parser;
    private int $maxDeep = 0;
    private int $maxPages = 0;
    private int $deep = 1;

    public function __construct(ReaderStorageInterface $readerStorage)
    {
        $this->readerStorage = $readerStorage;
    }

    public function getFactory(string $url): AbstractFactoryInterface
    {
        return new HtmlReaderSymfonyCrawlerParserFactory($url);
    }

    public function run(string $url, array $options = []): ?ImageParserInterface
    {
        if ($this->isRun($url)) {
            return null;
        }
        $parsedUrl = parse_url($url);
        $domain = !empty($parsedUrl['host']) ? $parsedUrl['host'] : '';

        $urlValidator = new TagUrlValidator($domain);

        if (!$urlValidator->attributeValidate($url)) {
            return null;
        }

        $readerParser = $this->getFactory($url);
        $this->parser = $readerParser->createParser();
        $html = '';

        foreach ($readerParser->createReader() as $itemHtml) {
            $html .= preg_replace('/\s{2,}|\t{2,}/', ' ', $itemHtml);
        }
        $this->parser->setHtml($html);
        $this->saveDataInStorage($url);
        $url_ = $this->getDomainFromUrl($url);
        $deep = $this->deep;

        foreach ($this->parser->getLinks() as $index => $url) {
            $url = $this->cleanUrl($url, $url_);
            if (!empty($options['read']) && is_callable($options['read'])) {
                if (!$options['read']($this->parser, $this, $url, $index)) {
                    break;
                }
            } else {
                $this->setDeep($deep + 1);
                $this->run($url);
            }
        }
        return $this->parser;
    }

    private function cleanUrl(string $url, string $domain): string
    {
        if (strpos($url, $domain) === false) {
            $url = $domain . $url;
        }
        $url = preg_replace('/\/+$/', '', $url);
        return $url;
    }

    public function getDomainFromUrl(string $url): string
    {
        $parsedUrl = parse_url($url);
        return !empty($parsedUrl['scheme']) ?
            $parsedUrl['scheme'] .
            '://'.
            $parsedUrl['host'] .
            (!empty($parsedUrl['port']) ? ':'.$parsedUrl['port'] : '')
            : '';
    }

    public function setMaxDeep(int $maxDeep): self
    {
        $this->maxDeep = $maxDeep;
        return $this;
    }

    public function setMaxPages(int $maxPages): self
    {
        $this->maxPages = $maxPages;
        return $this;
    }

    public function setDeep(int $deep): self
    {
        $this->deep = $deep;
        return $this;
    }

    public function getDeep(): int
    {
        return $this->deep;
    }

    private function saveDataInStorage(string $url): void
    {
        $this->readerStorage->saveLength();
        $this->readerStorage->setCurrentDeep($this->deep);
        $this->readerStorage->addUrls($url,
            [
                'executionTime' => $this->parser->getExecutionTime(),
                'deep' => $this->deep
            ]
        );
        $this->readerStorage->save();
    }

    public function isRun(string $url): bool
    {
        return ($this->readerStorage->isUrlLoaded($url)
            || ($this->maxDeep > 0 && $this->maxDeep <= $this->readerStorage->getCurrentDeep())
            || ($this->maxPages > 0 && $this->maxPages <= $this->readerStorage->getSavedLength())
        );
    }
}

