<?php

namespace Sip\ReaderManager\Test;

use Sip\ImageParser\Interfaces\ImageParserInterface;
use Sip\ReaderManager\Interfaces\ReaderManagerInterface;
use Sip\ReaderManager\Interfaces\ReaderStorageInterface;
use Sip\ReaderManager\ReaderManager;
use PHPUnit\Framework\TestCase;
use Sip\ReaderManager\FileStorage;

/*
 * Attention!!!
 * Run webserver before run this tests.
 * php -S localhost:8000
 */
class ReaderManagerTest extends TestCase
{
    private const HOST = "http://localhost:8000";

    public function testRun4LevelDeeps(): void
    {
        $this->runManagerByDeep(4);
    }

    public function testRun2LevelDeeps(): void
    {
        $this->runManagerByDeep(2);
    }

    public function testRunUserFunction(): void
    {
        $manager = $this->getManager($this->getStorage());

        $readDataUserFunction = function (ImageParserInterface $parser, ReaderManagerInterface $readerManager) {
            $this->assertEquals(1, $readerManager->getDeep());
        };

        $options = [
            'afterRead' => $readDataUserFunction
        ];

        $manager->run(self::HOST, "/", $options);
    }

    private function runManagerByDeep(int $deep): void
    {
        $storage = $this->getStorage();
        $this->getManager($storage)
            ->setMaxDeep($deep)
            ->run(self::HOST, "/");
        $this->assertEquals($deep, $storage->getCurrentDeep());
        $this->assertEquals($deep, count($storage->getUrls()));
    }

    private function getManager(ReaderStorageInterface $storage, int $deep = 1): ReaderManagerInterface
    {
        $readerManager = new ReaderManager($storage);
        $readerManager->setDeep($deep);
        return $readerManager;
    }

    private function getStorage(): ReaderStorageInterface
    {
        $storage = new FileStorage('localhost_8000.txt');
        $storage->clear();
        return $storage;
    }
}

