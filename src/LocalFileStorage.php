<?php

namespace pocifik\filestorage;

use Yii;
use yii\helpers\FileHelper;

class LocalFileStorage extends AbstractFileStorage
{
    public function copyFile(string $source, string $dest)
    {
        return copy($source, $dest);
    }

    public function removeFile(string $path)
    {
        return unlink($path);
    }

    public function getPublicPath()
    {
        $http = Yii::$app->request->isSecureConnection ? 'https' : 'http';
        return $http . '://' . Yii::$app->params['urls']['frontend_api'];
    }

    public function getRealPath()
    {
        return Yii::getAlias('@frontend_api/web');
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