<?php

namespace App\Controllers;

class LogController
{

    public const LOG_ROOT = '/var/log/';

    public static function view($name)
    {
        require_once APP_ROOT . '/resources/views/log.php';
    }

    /**
     * Выводит список файлов логов
     * @param string $name
     * @return void
     */
    public static function list(string $name)
    {
        $root = self::getDirRoot($name);

        if (!file_exists($root) || !is_dir($root)) {
            http_response_code(404);
            echo 'App logs not found.';
            exit;
        }

        $logs = [];

        $dir = opendir($root);
        while ($file = readdir($dir)) {
            if (str_ends_with($file, '.log')) {
                $logs[] = $file;
            }
        }

        rsort($logs);

        echo json_encode($logs, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Выводит содержимое файла лога
     * Можно отправить параметр offset, позволяющий обрезать получаемый лог
     * @param string $name
     * @param string $file
     * @return void
     */
    public static function content(string $name, string $file)
    {
        $root = self::getFileRoot($name, $file);

        if (!file_exists($root)) {
            http_response_code(404);
            echo 'App logs not found.';
            exit;
        }

        $offset = $_GET['offset'] ?? 0;
        $content = file_get_contents($root, FALSE, NULL, $offset);

        echo json_encode([
            'offset' => strlen($content) + $offset,
            'content' => $content
        ], JSON_UNESCAPED_UNICODE);
    }

    public static function getDirRoot(string $name): string
    {
        return self::LOG_ROOT . $name;
    }

    public static function getFileRoot(string $name, string $file): string
    {
        return self::getDirRoot($name) . '/' . $file;
    }

}