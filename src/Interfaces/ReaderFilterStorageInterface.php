<?php

namespace Sip\ReaderManager\Interfaces;

interface ReaderFilterStorageInterface
{
    public function __construct(string $name);

    public function getStorageDirectory(): string;
}