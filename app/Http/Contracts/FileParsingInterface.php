<?php
namespace App\Http\Contracts;

interface FileParsingInterface
{
  public function fileParser(mixed $file): array;
  public function getFileExtension(string $fileName): string;
}
