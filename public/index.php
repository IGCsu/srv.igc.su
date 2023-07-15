<?php

error_reporting(-1);
ini_set('display_errors', true);
ini_set('error_prepend_string', '<pre>');
ini_set('error_append_string', '</pre>');

require_once '../vendor/autoload.php';

// Load Config
require_once '../config/config.php';

\App\Env::load();

// Routes
require_once '../routes/web.php';