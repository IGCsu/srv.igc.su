<?php

namespace App\Controllers;

use App\Exception\UserException;
use App\Log;

class LogController
{
    /**
     * @throws UserException
     */
    public static function view(string $type, string $name)
    {
        Log::validate($type, $name);

        $appName = $type . DIRECTORY_SEPARATOR . $name;

        require_once APP_ROOT . '/resources/views/log.php';
    }

    /**
     * Выводит список файлов логов
     * @param string $type
     * @param string $name
     * @return void
     * @throws UserException
     */
    public static function list(string $type, string $name)
    {
        $root = Log::validate($type, $name);

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
     * @param string $type
     * @param string $name
     * @param string $file
     * @return void
     * @throws UserException
     */
    public static function content(string $type, string $name, string $file)
    {
        $root = Log::validate($type, $name, $file);

        $offset = $_GET['offset'] ?? 0;
        $content = file_get_contents($root, FALSE, NULL, $offset);

        echo json_encode([
            'offset' => strlen($content) + $offset,
            'content' => $content
        ], JSON_UNESCAPED_UNICODE);
    }

}