<?php

namespace pocifik\filestorage;

use yii\base\Component;

abstract class AbstractFileStorage extends Component
{
    /**
     * Копирует файл в другую директорию
     *
     * @param string $source Путь к файлу
     * @param string $dest Новый путь к файлу
     * @param bool $public Публичный ли файл
     *
     * @return bool
     */
    public abstract function copyFile(string $source, string $dest, bool $public = true): bool;

    /**
     * Удаляет файл
     *
     * @param string $path Путь к файлу
     *
     * @return bool
     */
    public abstract function removeFile(string $path): bool;

    /**
     * Возвращает url часть пути для публичного доступа к файлам
     *
     * @return string
     */
    public abstract function getPublicPath(): string;

    /**
     * Возвращает часть пути реального расположения файла, для работы с ним внутри системы
     *
     * @return string
     */
    public abstract function getRealPath(): string;

    /**
     * Проверяет существует ли файл
     *
     * @param string $path
     *
     * @return bool
     */
    public abstract function isFileExists(string $path): bool;

    /**
     * Создает указанную директорию
     *
     * @param string $path
     *
     * @return bool
     */
    public abstract function createDirectory(string $path): bool;

    /**
     * Возвращает путь к файлу на текущем сервере.
     * Если файл хранится в каком то облачном хранилище, то будет создана его локальная временная копия
     *
     * @param string $src
     *
     * @return string
     */
    public abstract function getFileLocally(string $src): string;

    /**
     * Возвращает содержимое файла
     *
     * @param string $path
     *
     * @return string
     */
    public abstract function getFileContent(string $path): string;
}