<?php

namespace Sip\ReaderManager\Interfaces;

use Sip\ImageParser\Interfaces\ImageParserInterface;

interface AbstractFactoryInterface
{
    public function createReader(): \Iterator;

    public function createParser(bool $isHrefSubDomain = false, bool $isImgSubDomain = false): ImageParserInterface;
}