<?php

namespace pocifik\filestorage;

use Yii;
use Aws\S3\S3Client;
use Aws\Credentials\Credentials;
use yii\helpers\FileHelper;

class S3FileStorage extends AbstractFileStorage
{
    public $key;
    public $secret;
    public $endpoint;
    public $url;
    public $bucket;

    /** @var S3Client */
    protected $s3_client;

    protected $prefix = 's3://';

    protected $tmp_files;
    protected $tmp_files_s3;

    public function init()
    {
        parent::init();

        $credentials = new Credentials($this->key, $this->secret);

        $this->s3_client = new S3Client([
            'version' => 'latest',
            'region'  => 'eu-central-1',
            'endpoint' => $this->endpoint,
            'credentials' => $credentials,
        ]);

        $this->s3_client->registerStreamWrapper();

        register_shutdown_function([$this, 'clearTmpFiles']);
    }

    public function copyFile(string $source, string $dest, bool $public = true): bool
    {
        //При копировании файлов, сохраняем их оригинальный путь на сервере, чтобы лишний раз не обращаться к S3
        $hash_s3 = md5($dest);
        $this->tmp_files_s3[$hash_s3] = $source;

        $acl = 'private';
        if ($public) {
            $acl = 'public-read';
        }

        return copy($source, $dest, stream_context_create([
            's3' => [
                'ACL' => $acl
            ]
        ]));
    }

    public function removeFile(string $path): bool
    {
        return unlink($path);
    }

    public function getPublicPath(): string
    {
        return "$this->url";
    }

    public function getRealPath(): string
    {
        return "{$this->prefix}{$this->bucket}";
    }

    public function isFileExists(string $path): bool
    {
        return file_exists($path);
    }

    public function createDirectory(string $path): bool
    {
        // Для S3 не нужно создавать директории вручную, это лишние запросы
        return true;
    }

    public function getFileLocally(string $src): string
    {
        $hash = md5($src);

        if (isset($this->tmp_files[$hash])) {
            return $this->tmp_files[$hash];
        }

        //Если файл находится в s3, сначала пытаемся найти его во временных файлах
        if (strpos($src, 's3://') !== false) {
            if (isset($this->tmp_files_s3[$hash])) {
                return $this->tmp_files_s3[$hash];
            }
            $tmp_arr = explode('.', $src);
            $ext = end($tmp_arr);
        }
        else {
            $ext = "png";
            $mimeType = FileHelper::getMimeType($src, null, false);
            if ($mimeType !== null) {
                $exts = FileHelper::getExtensionsByMimeType($mimeType);
                if (count($exts) > 0) {
                    $ext = end($exts);
                }
            }
        }

        $tmp_filename = tempnam('/tmp', 's3') . ".$ext";
        if (file_exists($src)) {
            file_put_contents($tmp_filename, file_get_contents($src));
        }
        $this->tmp_files[$hash] = $tmp_filename;
        return $tmp_filename;
    }

    public function getFileContent(string $path): string
    {
        $result = $this->s3_client->getObject([
            'Bucket' => $this->bucket,
            'Key' => $path,
        ]);
        return (string)$result['Body'];
    }

    public function clearTmpFiles(): bool
    {
        if (empty($this->tmp_files)) {
            return true;
        }

        foreach ($this->tmp_files as $tmp_file) {
            if (!unlink($tmp_file)) {
                Yii::error('Не удалось удалить временный файл ' . $tmp_file);
            }
        }

        $this->tmp_files = [];

        return true;
    }
}