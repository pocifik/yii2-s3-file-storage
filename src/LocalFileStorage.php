<?php

namespace pocifik\filestorage;

use Yii;
use yii\helpers\FileHelper;

class LocalFileStorage extends AbstractFileStorage
{
    public string $public_path;
    public string $real_path;

    public function copyFile(string $source, string $dest, bool $public = true)
    {
        return copy($source, $dest);
    }

    public function removeFile(string $path)
    {
        return unlink($path);
    }

    public function getPublicPath()
    {
        return $this->public_path;
    }

    public function getRealPath()
    {
        return $this->real_path;
    }

    public function isFileExists(string $path)
    {
        return file_exists($path);
    }

    public function createDirectory(string $path)
    {
        return FileHelper::createDirectory($path);
    }

    public function getFileLocally(string $src)
    {
        return $src;
    }
}