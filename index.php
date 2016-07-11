<?php
chdir(dirname(__DIR__));

require(__DIR__ . '/vendor/autoload.php');
require(__DIR__ . '/App.php');

(new App)->run();
