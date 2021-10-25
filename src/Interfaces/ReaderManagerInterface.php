<?php

namespace Sip\ReaderManager\Interfaces;

use Sip\ImageParser\Interfaces\ImageParserInterface;

interface ReaderManagerInterface
{
    public function __construct(ReaderStorageInterface $readerStorage);

    public function run(string $url, array $options = []): ?ImageParserInterface;

    public function getDomainFromUrl(string $url): string;

    public function setMaxDeep(int $maxDeep): self;

    public function setMaxPages(int $maxPages): self;

    public function setDeep(int $deep): self;

    public function getDeep(): int;

    public function isNoHrefSubDomain(): bool;

    public function setIsNoHrefSubDomain(bool $isNoHrefSubDomain): self;

    public function isNoImageSubDomain(): bool;

    public function setIsNoImageSubDomain(bool $isNoImageSubDomain): self;

}