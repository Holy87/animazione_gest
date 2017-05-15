<?php
/**
 * Created by PhpStorm.
 * User: Gold Service
 * Date: 11/05/2017
 * Time: 12:02
 */
class AccountController {
    public static function login($username, $password) {
        echo json_encode(['ok' => false, 'user' => $username, 'pass' => $password]);
    }

    public static function logout() {
        $_SESSION['user_id'] = null;
        $_SESSION = [];
        session_destroy();
        header('location:home');
    }
}