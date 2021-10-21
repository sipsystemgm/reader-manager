<?php

namespace Sip\ReaderManager\Interfaces;

use Sip\ImageParser\Interfaces\ImageParserInterface;

interface ReaderManagerInterface
{
    public function __construct(ReaderStorageInterface $readerStorage);

    public function run(string $domain, string $url, array $options = []): ?ImageParserInterface;

    public function setMaxDeep(int $maxDeep): self;

    public function setMaxPages(int $maxPages): self;

    public function setDeep(int $deep): self;

    public function getDeep(): int;

}