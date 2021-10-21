<?php

namespace Sip\ReaderManager;

use Sip\ReaderManager\Interfaces\ReaderStorageInterface;

abstract class AbstractStorage implements ReaderStorageInterface
{
    protected int $savedLength = 0;
    protected int $currentDeep = 0;
    protected array $urls = [];
    protected string $storagePath;

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

    public function getUrls(): array
    {
        return $this->urls;
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