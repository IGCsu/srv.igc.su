<?php

use App\Controllers\GitController;
use App\Controllers\LogController;
use App\Router;

Router::add('gitBotWebhook', '/git/bot/webhook')
    ->setDefault('_controller', [GitController::class, 'botWebhook'])
    ->setMethods(['POST']);

Router::add('viewLog', '/log/{name}')
    ->setDefault('_controller', [LogController::class, 'view'])
    ->addRequirements(['name'])
    ->setMethods(['GET']);

Router::add('getLogList', '/log/{name}/list')
    ->setDefault('_controller', [LogController::class, 'list'])
    ->addRequirements(['name'])
    ->setMethods(['GET']);

Router::add('getLogContent', '/log/{name}/content/{file}')
    ->setDefault('_controller', [LogController::class, 'content'])
    ->addRequirements(['name', 'file'])
    ->setMethods(['GET']);

Router::init();