<?php

use Composer\Autoload\ClassLoader;

$loader = require __DIR__ . '/../vendor/autoload.php';
$loader->add('', __DIR__);
$loader->register();

return $loader;
