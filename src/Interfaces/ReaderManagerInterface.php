<?php

namespace Sip\ReaderManager\Interfaces;

interface ReaderManagerInterface
{
    public function __construct(ReaderStorageInterface $readerStorage);

    public function run(string $domain, string $url, ?callable $itemUserFunction = null): void;

    public function setMaxDeep(int $maxDeep): self;

    public function setMaxPages(int $maxPages): self;

    public function setDeep(int $deep): self;

    public function getDeep(): int;

}