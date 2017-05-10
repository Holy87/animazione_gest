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

if (isset($_GET['controller']) && isset($_GET['action'])) {
    $controller = $_GET['controller'];
    $action     = $_GET['action'];
  } else {
    $controller = 'pages';
    $action     = 'home';
  }

require_once('application/views/main_layout.php');
