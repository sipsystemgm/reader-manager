<?php

namespace Sip\ReaderManager;

use Sip\ReaderManager\Interfaces\ReaderFilterStorageInterface;

class FileStorage extends AbstractStorage implements ReaderFilterStorageInterface
{
    protected string $storagePath = '';

    public function __construct(string $name)
    {
        $this->storagePath = $this->getStorageDirectory(). $name;
        if (!file_exists($this->storagePath)) {
           $this->save();
        } else {
            $data = json_decode(file_get_contents($this->storagePath), true);
            $this->savedLength = $data['savedLength'];
            $this->currentDeep = $data['currentDeep'];
            $this->urls = $data['urls'];
        }
    }

    public function getStorageDirectory(): string
    {
        return  __DIR__.'/../storage/';
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

