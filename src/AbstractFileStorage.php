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
     *
     * @return bool
     */
    public abstract function copyFile(string $source, string $dest);

    /**
     * Удаляет файл
     *
     * @param string $path Путь к файлу
     *
     * @return bool
     */
    public abstract function removeFile(string $path);

    /**
     * Возвращает url часть пути для публичного доступа к файлам
     *
     * @return string
     */
    public abstract function getPublicPath();

    /**
     * Возвращает часть пути реального расположения файла, для работы с ним внутри системы
     *
     * @return string
     */
    public abstract function getRealPath();

    /**
     * Проверяет существует ли файл
     *
     * @param string $path
     *
     * @return bool
     */
    public abstract function isFileExists(string $path);

    /**
     * Создает указанную директорию
     *
     * @param string $path
     *
     * @return string
     */
    public abstract function createDirectory(string $path);

    /**
     * Возвращает путь к файлу на текущем сервере.
     * Если файл хранится в каком то облачном хранилище, то будет создана его локальная временная копия
     *
     * @param string $src
     *
     * @return string
     */
    public abstract function getFileLocally(string $src);
}