<?php

namespace App\Http\Contracts;

interface FileParsingByTypeInterface
{
    public static function parseFile(mixed $file): array;
}
