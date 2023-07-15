<?php

use App\Exception\UserException;

/** @var UserException $this */

?><!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="shortcut icon" href="favicon.png">
    <link rel="stylesheet" href="/css/app.css">

    <title>Logs</title>
</head>
<body>
<div class="error">

    <div class="error-code"><?php echo $this->getCode() ?></div>
    <div class="error-message"><?php echo $this->getMessage() ?></div>

</div>

<script type="text/javascript" src="/js/jquery-3.6.3.min.js"></script>
<script type="text/javascript" src="/js/app.min.js"></script>
</body>
</html>