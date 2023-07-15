<?php

namespace App\Exception;

use Exception;

/**
 * Эксепшен, который будет возвращён клиенту
 */
class UserException extends Exception
{
    public const DEFAULT_CODE = 400;
    public const DEFAULT_MESSAGE = 'Something went wrong... Please try again.';

    /**
     * Отображает клиенту ошибку
     * @return void
     */
    public function show()
    {
        if (!$this->code) {
            $this->code = self::DEFAULT_CODE;
        }

        if (!$this->message) {
            $this->message = self::DEFAULT_MESSAGE;
        }

        http_response_code($this->code);

        require_once APP_ROOT . '/resources/views/error.php';
    }
}