<?php

namespace App;

use Dotenv\Dotenv;

class Env
{
    public static function load()
    {
        $dotenv = Dotenv::createImmutable(APP_ROOT);
        $dotenv->load();
    }

    /**
     * @param string $key
     * @return string
     */
    public static function get(string $key): string
    {
        if (!isset($_ENV[$key])) {
            throw new \InvalidArgumentException(sprintf('Env key "%s" not found', $key));
        }

        return $_ENV[$key];
    }

    public static function getGitSecret(): string
    {
        return self::get('GIT_SECRET');
    }
}