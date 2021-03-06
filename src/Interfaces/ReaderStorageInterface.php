<?php

namespace Sip\ReaderManager\Interfaces;

interface ReaderStorageInterface
{
    public function saveLength(): self;

    public function getSavedLength(): int;

    public function getCurrentDeep(): int;

    public function setCurrentDeep(int $currentDeep): self;

    public function isUrlLoaded(string $url): bool;

    public function getUrls(): array;

    public function getUrl(string $url): ?array;

    public function addUrls(string $url, array $urlData = []): self;

    public function save(): self;

    public function clear(): self;
}