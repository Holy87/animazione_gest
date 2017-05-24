<?php
/**
 * Created by PhpStorm.
 * User: Francesco
 * Date: 21/03/2017
 * Time: 21:11
 */
session_start();

require_once 'config.php';
require_once 'application/controllers/controller.php';
require_once 'application/db_connection.php';
require_once 'application/routes.php';


require_once 'config.php';
require_once 'application/controllers/controller.php';
require_once 'application/db_connection.php';
require_once 'application/routes.php';

if(isset($_SESSION['user_id']) || in_array($_GET['page'], PUBLIC_PAGES))
{
    if (isset($_GET['page']) && strlen($_GET['page']) > 1) {
        if (isset($_GET['action']))
            $action = $_GET['action'];
        else
            $action = 'default';
        if ($_GET['page'] == 'services') {
            $controller = 'services';
            require_once('application/controllers/services.php');
        }
        else {
            $controller = 'pages';
            $action = $_GET['page'];
            if($action == 'logout')
                load_page(null);
            else
                require_once('application/views/main_layout.php');
        }
    } else {
        header('location:home');
    }
} else
    header('location:login');

