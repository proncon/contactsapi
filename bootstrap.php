<?php

require 'vendor/autoload.php';
$configFile = dirname(__FILE__) . '/config.php';

if (is_readable($configFile)) {
    require_once $configFile;
}
else
{
	die("Missing config.php file.\n");
}
