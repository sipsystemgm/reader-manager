<?php

namespace Sip\ReaderManager;

use Sip\ReaderManager\Interfaces\ReaderStorageInterface;

class FileStorage extends AbstractStorage
{
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

    public function isUrlLoaded(string $url): bool
    {
        return in_array($url, $this->urls);
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

}

