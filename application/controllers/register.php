<?php
/**
 * Created by PhpStorm.
 * User: Francesco
 * Date: 29/03/2017
 * Time: 18:22
 */
require_once '../db_connection.php';

$user = $_POST['user'];
$mail = $_POST['mail'];
$pass = sha1($_POST['password']);

$query = "INSERT INTO users (user_name, user_mail, user_password) VALUES (:user, :mail, :pass)";
$link = Db::getInstance();
$stmt = $link->prepare($query);
$stmt->bindParam('user', $user);
$stmt->bindParam('mail', $mail);
$stmt->bindParam('pass', $pass);

$stmt->execute();
if ($link->lastInsertId() > 0) {
    header('location: home');
} else { //TODO: Add the correct location
    header('location: error');
}
