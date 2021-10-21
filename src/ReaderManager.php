<?php

namespace Sip\ReaderManager;

use Sip\ImageParser\Interfaces\ImageParserInterface;
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

    public function run(string $domain, string $url, array $options = []): ?ImageParserInterface
    {
        if ($this->isRun($url)) {
            return null;
        }

        $readerParser = new HtmlReaderSymfonyCrawlerParserFactory($domain . $url);
        $parser = $readerParser->createParser();
        $this->saveDataInStorage($url);
        $html = '';

        foreach ($readerParser->createReader() as $itemHtml) {
            $html .= preg_replace('/\s{2,}|\t{2,}+/', ' ', $itemHtml);
        }

        $parser->setHtml($html);
        if (!empty($options['afterRead']) && is_callable($options['afterRead'])) {
            $options['afterRead']($parser, $this);
            return $parser;
        }

        foreach ($parser->getLinks() as $link) {
            $link = str_replace([$domain, ' '], '', $link);
            $this->setDeep($this->deep + 1);
            $this->run($domain, $link);
        }

        return $parser;
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
        $this->readerStorage->addUrls([$url]);
        $this->readerStorage->save();
    }

    private function isRun(string $url): bool
    {
        return ($this->readerStorage->isUrlLoaded($url)
            || ($this->maxDeep > 0 && $this->maxDeep <= $this->readerStorage->getCurrentDeep())
            || ($this->maxPages > 0 && $this->maxPages <= $this->readerStorage->getSavedLength())
        );
    }
}

