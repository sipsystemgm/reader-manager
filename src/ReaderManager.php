<?php

namespace Sip\ReaderManager;

use Sip\ReaderManager\Interfaces\ReaderManagerInterface;
use Sip\ReaderManager\Interfaces\ReaderStorageInterface;
use Sip\ReaderManager\Factory\HtmlReaderSymfonyCrawlerParserFactory;
use Sip\ReaderManager\Factory\ReaderParserFactory;

class ReaderManager implements ReaderManagerInterface
{
    private ReaderStorageInterface $readerStorage;
    private int $maxDeep = 0;
    private int $maxPages = 0;
    private int $deep = 0;

    public function __construct(ReaderStorageInterface $readerStorage)
    {
        $this->readerStorage = $readerStorage;
    }

    public function run(string $domain, string $url, ?callable $itemUserFunction = null): void
    {
        if ($this->readerStorage->isUrlLoaded($url)
            || ($this->maxDeep > 0 && $this->maxDeep <= $this->readerStorage->getCurrentDeep())
            || ($this->maxPages > 0 && $this->maxPages <= $this->readerStorage->getSavedLength())
        ) {
            return;
        }

        $readerParser = new HtmlReaderSymfonyCrawlerParserFactory($domain .$url);
        $parser = $readerParser->createParser();

        $this->readerStorage->saveLength();
        $this->readerStorage->setCurrentDeep($this->deep);
        $this->readerStorage->addUrls([$url]);
        $this->readerStorage->save();

        foreach ($readerParser->createReader() as $index => $itemHtml) {

            $itemHtml_ = preg_replace('/\s+$/', '', $itemHtml);

            if (strrpos($itemHtml_, '>') !== false
                && strrpos($itemHtml_, '>') + 1  == strlen($itemHtml_)) {

                $parser->setHtml($itemHtml_);
                if ($itemUserFunction !== null) {
                    $itemUserFunction($parser, $this, $index);
                } else {
                    foreach ($parser->getLinks() as $link) {
                        $link = str_replace($domain, '', $link);
                        $this->setDeep($this->deep + 1);
                        $this->run($domain, $link);
                    }
                }
            }
        }
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
}

