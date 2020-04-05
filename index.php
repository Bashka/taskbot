<?php

use Bashka\Taskbot\App;

chdir(dirname(__DIR__));

require(__DIR__ . '/vendor/autoload.php');

$configs = [
    __DIR__ . '/config/global.php',
    __DIR__ . '/config/local.php',
];

$app = new App($configs);
$app->run();
