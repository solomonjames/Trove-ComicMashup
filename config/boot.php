<?php

// Define application environment
defined('AE') || define('AE', (getenv('AE') ? getenv('AE') : 'development'));

// Define path to project root directory
define("BP", dirname(dirname(__FILE__)));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(BP . '/library'),
    get_include_path()
)));

// Servers are in Central time, so lets set all data to Eastern
date_default_timezone_set('America/New_York');

// Setup autoloader
require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance()->registerNamespace("Aj");

// Session Init
Aj_Setup::session();

Zend_Registry::set("config", new Zend_Config_Ini(dirname(__FILE__) . "/application.ini"));