<?php
// Init and load configuration
$config = array();
require 'vendor/autoload.php';
$configFile = dirname(__FILE__) . '/config.php';

if (is_readable($configFile)) {
    require_once $configFile;
} 
else
{
	die("Missing config.php file.\n");
}

// Create Application
use API\Application;
$app = new Application();