<?php

namespace App\Controllers;

use App\Env;
use App\Exception\UserException;

class GitController
{
    /**
     * Перезапускает службу бота
     * @return void
     * @throws UserException
     */
    public static function botWebhook()
    {
        [$algo, $hash] = explode('=', $_SERVER['HTTP_X_HUB_SIGNATURE'], 2);
        $rawPost = file_get_contents('php://input');

        if (!hash_equals($hash, hash_hmac($algo, $rawPost, Env::getGitSecret()))) {
            throw new UserException('Hook secret does not match', 401);
        }

        shell_exec('sudo -S systemctl restart IGCb.service 2>&1');
    }
}