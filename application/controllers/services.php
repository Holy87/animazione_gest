<?php
/**
 * Created by PhpStorm.
 * User: frbos
 * Date: 13/05/2017
 * Time: 12:35
 */
require_once ABS_PATH.'/application/controllers/account_controller.php';

if(!isset($action))
    $action = 'default';
switch($action) {
    case 'login':
        AccountController::login($_POST['user'], $_POST['password']);
        break;
    case 'logout':
        AccountController::logout();
        break;
}