<?php

namespace Sip\ReaderManager\Interfaces;

interface ReadCacheInterface
{
    public function __construct(string $indexName, array $options);
}