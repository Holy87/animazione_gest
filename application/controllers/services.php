<?php
/**
 * Created by PhpStorm.
 * User: frbos
 * Date: 13/05/2017
 * Time: 12:35
 */

require_once ABS_PATH.'/application/controllers/mail_controller.php';
require_once ABS_PATH.'/application/controllers/account_controller.php';
require_once ABS_PATH.'/application/controllers/item_controller.php';
require_once ABS_PATH.'/application/controllers/user_controller.php';
require_once ABS_PATH.'/application/controllers/upload_controller.php';
require_once ABS_PATH.'/application/controllers/theme_controller.php';
require_once ABS_PATH.'/application/controllers/party_controller.php';

function process_request() {
    global $action;
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
            echo ItemController::create_item();
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
        case 'change_phone':
            echo UserController::changePhone();
            break;
        case 'change_password':
            echo UserController::changePassword();
            break;
        case 'recover_passeord':
            echo UserController::request_recovery_password();
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
        case 'get_active_users':
            echo UserController::get_avaiable_animators();
            break;
        case 'get_themes':
            echo ThemeController::get_themes();
            break;
        case 'save_theme':
            echo ThemeController::edit_theme();
            break;
        case 'delete_theme':
            echo ThemeController::delete_theme();
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
        case 'add_theme_item':
            echo ThemeController::add_item();
            break;
        case 'theme_price':
            echo ThemeController::get_theme_price();
            break;
        case 'get_active_parties':
            echo PartyController::get_active_parties();
            break;
        case 'get_passed_parties':
            echo PartyController::get_passed_parties();
            break;
        case 'create_party':
            echo PartyController::create_party();
            break;
        case 'delete_party':
            echo PartyController::delete_party();
            break;
        case 'save_party':
            echo PartyController::save_party_informations();
            break;
        case 'get_party_animators':
            echo PartyController::get_party_animators();
            break;
        case 'add_party_animator':
            echo PartyController::add_animator();
            break;
        case 'remove_party_animator':
            echo PartyController::remove_animator();
            break;
        case 'get_party_items':
            echo PartyController::get_items();
            break;
        case 'add_party_item':
            echo PartyController::add_item();
            break;
        case 'remove_party_item':
            echo PartyController::remove_item();
            break;
        case 'incr_party_item_n':
            echo PartyController::increase_item_number();
            break;
        case 'decr_party_item_n':
            echo PartyController::decrease_item_number();
            break;
        case 'update_user_version':
            echo UserController::update_user_version();
            break;
        default:
            echo $action."   ".print_r($_GET);
            break;
    }
}

try {
    process_request();
} catch (Exception $e) {
    echo json_encode(['ok' => false, 'reason' => $e->getMessage(), 'code' => $e->getCode()]);
}