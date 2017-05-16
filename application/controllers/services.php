<?php
/**
 * Created by PhpStorm.
 * User: frbos
 * Date: 13/05/2017
 * Time: 12:35
 */
require_once ABS_PATH.'/application/controllers/account_controller.php';
require_once ABS_PATH.'/application/controllers/item_controller.php';

if(!isset($action))
    $action = 'default';
switch($action) {
    case 'login':
        AccountController::login($_POST['user'], $_POST['password']);
        break;
    case 'logout':
        AccountController::logout();
        break;
    case 'get_items':
        ItemController::get_all_items();
        break;
    case 'update_item':
        ItemController::save_item();
        break;
    case 'delete_item':
        ItemController::delete_item();
        break;
    case 'create_item':
        ItemController::create_item();
        break;
    case 'get_item':
        ItemController::get_item();
        break;
}