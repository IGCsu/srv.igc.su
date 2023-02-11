<?php

use App\Controllers\GitController;
use App\Router;

Router::add('gitBotWebhook', '/git/bot/webhook')
    ->setDefault('_controller', [GitController::class, 'botWebhook'])
    ->setMethods(['POST']);

Router::init();