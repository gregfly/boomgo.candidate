<?php
include_once '../autoloader.php';

spl_autoload_register([new Autoloader(), 'autoload']);

\app\Glob::$app = new \app\base\Application();

\app\Glob::$app->run();
