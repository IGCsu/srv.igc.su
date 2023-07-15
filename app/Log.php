<?php

namespace App;

use App\Exception\UserException;

class Log
{
    public const TYPE_BOT = 'bot';
    public const TYPE_WWW = 'www';

    public const ROOT = '/var/log/';

    protected static array $allowedTypes = [
        self::TYPE_BOT,
        self::TYPE_WWW
    ];

    /**
     * Валидирует путь к файлу и возвращает его
     * @param string $type
     * @param string $name
     * @param string|null $file
     * @return string
     * @throws UserException
     */
    public static function validate(string $type, string $name, string $file = null): string
    {
        if (!Log::isAllowedType($type)) {
            throw new UserException('Type is not allowed', 403);
        }

        $root = Log::getDirRoot($type, $name);

        if (!file_exists($root) || !is_dir($root)) {
            throw new UserException(sprintf('Not found logs for "%s"', $name), 404);
        }

        if (!is_null($file)) {
            $root = Log::getFileRoot($type, $name, $file);

            if (!file_exists($root)) {
                throw new UserException(sprintf('Not found log file "%s" for "%s"', $file, $name), 404);
            }
        }

        return $root;
    }

    public static function getDirRoot(string $type, string $name): string
    {
        return self::ROOT . $type . DIRECTORY_SEPARATOR . $name;
    }

    public static function getFileRoot(string $type, string $name, string $file): string
    {
        return self::getDirRoot($type, $name) . DIRECTORY_SEPARATOR . $file;
    }

    public static function isAllowedType(string $type): bool
    {
        return in_array($type, self::$allowedTypes);
    }
}