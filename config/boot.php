<?php

// Define application environment
defined('AE') || define('AE', (getenv('AE') ? getenv('AE') : 'development'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(BP . '/library'),
    get_include_path()
)));

// Servers are in Central time, so lets set all data to Eastern
date_default_timezone_set('America/New_York');