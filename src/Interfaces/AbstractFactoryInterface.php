<?php

namespace Sip\ReaderManager\Interfaces;

use Sip\ImageParser\Interfaces\ImageParserInterface;

interface AbstractFactoryInterface
{
    public function createReader(): \Iterator;

    public function createParser(): ImageParserInterface;
}