<?php
/**
 * Created by PhpStorm.
 * User: Francesco
 * Date: 22/03/2017
 * Time: 21:49
 */

// -- DATABASE SETTINGS
// File di configurazione dell'applicazione

// Database host
define('DB_HOST', 'localhost');
// Database name
define('DB_NAME', 'animazione');
// User login
define('DB_USER', 'root');
// User password
define('DB_PASS', '');

// -- APP SETTINGS

// Author
define('AUTHOR', 'Francesco Bosso');

// Application name
define ('APP_NAME', 'Gestionale A.S.P.');

// Favicon
define('FAVICON', '');

// Web Root
define('W_ROOT', $_SERVER['SERVER_NAME'].'/animazione');

// Assets folder
define ('ASSETS', W_ROOT.'/assets');

// Pagine che sono permesse senza effettuare il login
define ('PUBLIC_PAGES', ['login', 'logout', 'services']);

if ( !defined('ABS_PATH') )
    define('ABS_PATH', dirname(__FILE__) . '/');
