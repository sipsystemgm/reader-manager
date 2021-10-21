<?php

namespace Sip\ReaderManager\Interfaces;

interface ReaderStorageInterface
{
    public function __construct(string $name);

    public function saveLength(): self;
    public function getSavedLength(): int;

    public function getCurrentDeep(): int;
    public function setCurrentDeep(int $currentDeep): self;

    public function isUrlLoaded(string $url): bool;
    public function getUrls(): array;
    public function addUrls(?array $urlArray): self;

    public function save(): self;
    public function clear(): self;
}