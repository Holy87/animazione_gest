<?php
/**
 * Created by PhpStorm.
 * User: Francesco
 * Date: 29/03/2017
 * Time: 12:18
 */
require_once '../db_connection.php';

function check_user() {
    $query = "SELECT user_name FROM users WHERE user_name = :name";
    $stmt = Db::getInstance()->prepare($query);
    $stmt->bindParam('name', $_GET['value']);
    $stmt->execute();
    if ($stmt->rowCount() > 0)
        echo 'used';
    else
        echo 'free';
}

function check_mail() {
    $query = "SELECT user_mail FROM users WHERE user_mail = :mail";
    $stmt = Db::getInstance()->prepare($query);
    $stmt->bindParam('mail', $_GET['value']);
    $stmt->execute();
    if ($stmt->rowCount() > 0)
        echo 'used';
    else
        echo 'free';
}

if (isset($_GET['check'])) {
    if($_GET['check'] == 'user') {
        check_user();
    } elseif ($_GET['check'] == 'mail') {
        check_mail();
    }
}