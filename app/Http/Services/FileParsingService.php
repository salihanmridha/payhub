<?php

namespace App\Http\Services;

use App\Http\Contracts\FileParsingInterface;
use Exception;


class FileParsingService implements FileParsingInterface
{
    /**
     * @throws Exception
     */
    public function fileParser(mixed $file): array
    {
        $fileExtension = $this->getFileExtension($file->getClientOriginalName());

        $fullClassName = 'App\\Http\\Services\\' . ucfirst($fileExtension) . "FileParser";

        if (class_exists($fullClassName)) {
            return (new $fullClassName())->parseFile($file);
        } else {
            throw new Exception('Invalid file type format: ' . $fullClassName);
        }
    }

    /**
     * @param string $fileName
     * @return string
     */
    public function getFileExtension(string $fileName): string
    {
        return pathinfo($fileName, PATHINFO_EXTENSION);
    }
}
