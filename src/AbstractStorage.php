<?php

namespace Sip\ReaderManager;

use Sip\ReaderManager\Interfaces\ReaderStorageInterface;

abstract class AbstractStorage implements ReaderStorageInterface
{
    protected int $savedLength = 0;
    protected int $currentDeep = 0;
    protected array $urls = [];

    public function saveLength(): self
    {
        $this->savedLength++;
        return $this;
    }

    public function isUrlLoaded(string $url): bool
    {
        return isset($this->urls[md5($url)]);
    }

    public function addUrls(string $url, array $urlData = []): self
    {
        if (!$this->isUrlLoaded($url)) {
            $urlData['url'] = $url;
            $this->urls[md5($url)] = $urlData;
        }

        return $this;
    }

    public function getSavedLength(): int
    {
        return $this->savedLength;
    }

    public function getCurrentDeep(): int
    {
        return $this->currentDeep;
    }

    public function setCurrentDeep(int $currentDeep): self
    {
        $this->currentDeep = $currentDeep;
        return $this;
    }

    public function getUrls(): array
    {
        return $this->urls;
    }

    public function getUrl(string $url): ?array
    {
        $key = md5($url);
        if (!empty($this->urls[$key])) {
            return $this->urls[$key];
        }
        return null;
    }

    public function clear(): self
    {
        $this->savedLength = 0;
        $this->currentDeep = 0;
        $this->urls = [];
        $this->save();
        return $this;
    }
}