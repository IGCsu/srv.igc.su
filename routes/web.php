<?php

use App\Controllers\GitController;
use App\Controllers\LogController;
use App\Router;

Router::add('gitBotWebhook', '/git/bot/webhook')
    ->setDefault('_controller', [GitController::class, 'botWebhook'])
    ->setMethods(['POST']);

Router::add('viewLog', '/log/{type}/{name}')
    ->setDefault('_controller', [LogController::class, 'view'])
    ->addRequirements(['type', 'name'])
    ->setMethods(['GET']);

Router::add('getLogList', '/log/{type}/{name}/list')
    ->setDefault('_controller', [LogController::class, 'list'])
    ->addRequirements(['type', 'name'])
    ->setMethods(['GET']);

Router::add('getLogContent', '/log/{type}/{name}/content/{file}')
    ->setDefault('_controller', [LogController::class, 'content'])
    ->addRequirements(['type', 'name', 'file'])
    ->setMethods(['GET']);

Router::init();