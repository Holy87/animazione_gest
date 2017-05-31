<?php
/**
 * Created by PhpStorm.
 * User: frbos
 * Date: 13/05/2017
 * Time: 12:35
 */
require_once ABS_PATH.'/application/controllers/account_controller.php';
require_once ABS_PATH.'/application/controllers/item_controller.php';
require_once ABS_PATH.'/application/controllers/user_controller.php';
require_once ABS_PATH.'/application/controllers/upload_controller.php';
require_once ABS_PATH.'/application/controllers/theme_controller.php';

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
        echo ItemController::save_item();
        break;
    case 'delete_item':
        echo ItemController::delete_item();
        break;
    case 'create_item':
        ItemController::create_item();
        break;
    case 'get_item':
        ItemController::get_item();
        break;
    case 'check_mail':
        echo UserController::checkMail();
        break;
    case 'change_mail':
        echo UserController::changeMail();
        break;
    case 'change_password':
        echo UserController::changePassword();
        break;
    case 'change_name':
        echo UserController::changeName();
        break;
    case 'photo-upload':
        UploadController::profile_image();
        break;
    case 'get_users':
        echo UserController::get_users();
        break;
    case 'create_user':
        echo UserController::create_user();
        break;
    case 'edit_user_master':
        echo UserController::edit_user();
        break;
    case 'delete_user':
        echo UserController::delete_user();
        break;
    case 'get_themes':
        echo ThemeController::get_themes();
        break;
    case 'save_theme':
        echo ThemeController::edit_theme();
        break;
    case 'get_theme_items':
        echo ThemeController::get_theme_items();
        break;
    case 'incr_item':
        echo ThemeController::increase_item_number();
        break;
    case 'decr_item':
        echo ThemeController::decrease_item_number();
        break;
    case 'delete_theme_item':
        echo ThemeController::remove_item();
        break;
    default:
        echo $action."   ".print_r($_GET);
        break;
}