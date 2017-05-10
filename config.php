<?php
/**
 * Created by PhpStorm.
 * User: Francesco
 * Date: 22/03/2017
 * Time: 21:49
 */

// -- DATABASE SETTINGS

// Database host
define('DB_HOST', 'localhost');
// Database name
define('DB_NAME', 'animazione');
// User login
define('DB_USER', 'root');
// User password
define('DB_PASS', '');

if ( !defined('ABS_PATH') )
    define('ABS_PATH', dirname(__FILE__) . '/');

define ('APP_NAME', 'Gestionale A.S.P.');