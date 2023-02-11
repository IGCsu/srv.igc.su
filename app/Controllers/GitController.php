<?php

namespace App\Controllers;

class GitController
{
    /**
     * Перезапускает службу бота
     * @return void
     */
    public static function botWebhook()
    {
        shell_exec('sudo -S systemctl restart IGCbV2.service 2>&1');
    }
}