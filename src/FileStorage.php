<?php

namespace Sip\ReaderManager;

class FileStorage extends AbstractStorage
{
    protected string $storagePath =  __DIR__.'/../storage/';

    public function __construct(string $name)
    {
        $this->storagePath .=$name;
        if (!file_exists($this->storagePath)) {
           $this->save();
        } else {
            $data = json_decode(file_get_contents($this->storagePath), true);
            $this->savedLength = $data['savedLength'];
            $this->currentDeep = $data['currentDeep'];
            $this->urls = $data['urls'];
        }
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

