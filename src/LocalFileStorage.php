<?php

namespace pocifik\filestorage;

use Yii;
use yii\helpers\FileHelper;

class LocalFileStorage extends AbstractFileStorage
{
    public ?string $public_path = null;
    public ?string $real_path = null;

    public function copyFile(string $source, string $dest, bool $public = true): bool
    {
        return copy($source, $dest);
    }

    public function removeFile(string $path): bool
    {
        return unlink($path);
    }

    public function getPublicPath(): string
    {
        return $this->public_path;
    }

    public function getRealPath(): string
    {
        return $this->real_path;
    }

    public function isFileExists(string $path): bool
    {
        return file_exists($path);
    }

    public function createDirectory(string $path): bool
    {
        return FileHelper::createDirectory($path);
    }

    public function getFileLocally(string $src): string
    {
        return $src;
    }

    public function getFileContent(string $path): string
    {
        return file_get_contents($path);
    }
}