<?php

namespace Sip\ReaderManager\Test;

use Sip\ReaderManager\Interfaces\ReaderStorageInterface;
use Sip\ReaderManager\FileStorage;

class FileStorageTest extends AbstractStorageTest
{
    protected function getStorage(): ReaderStorageInterface
    {
        return new FileStorage('test-data.txt');
    }
}
