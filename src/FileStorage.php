<?php

namespace Sip\ReaderManager;

use Sip\ReaderManager\Interfaces\ReaderStorageInterface;

class FileStorage implements ReaderStorageInterface
{
    private int $savedLength = 0;
    private int $currentDeep = 0;
    private array $urls = [];
    private string $storagePath;

    public function __construct(string $name)
    {
        $this->storagePath = __DIR__.'/../storage/'.$name;
        if (!file_exists($this->storagePath)) {
           $this->save();
        } else {
            $data = json_decode(file_get_contents($this->storagePath));
            $this->savedLength = $data->savedLength;
            $this->currentDeep = $data->currentDeep;
            $this->urls = $data->urls;
        }
    }

    public function saveLength(): self
    {
        $this->savedLength++;
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

    public function isUrlLoaded(string $url): bool
    {
        return in_array($url, $this->urls);
    }

    public function getUrls(): array
    {
        return $this->urls;
    }

    public function addUrls(?array $urlArray): self
    {
        foreach ($urlArray as $url) {
            if (!$this->isUrlLoaded($url)) {
                $this->urls[] = $url;
            }
        }
        return $this;
    }

    public function save(): self
    {
        file_put_contents($this->storagePath, json_encode([
            'savedLength' => $this->getSavedLength(),
            'currentDeep' => $this->getCurrentDeep(),
            'urls' => $this->urls
        ]));
        return $this;
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

