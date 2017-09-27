<?php
/**
 * Created by PhpStorm.
 * User: Gold Service
 * Date: 11/05/2017
 * Time: 12:02
 */
class AccountController {
    public static function login($username, $password) {
        $user_id = self::try_login($username, $password);
        if ($user_id != null) {
            $_SESSION['user_id'] = $user_id;
            echo json_encode(['ok' => true]);
        } else
            echo json_encode(['ok' => false]);
    }

    public static function logout() {
        $_SESSION['user_id'] = null;
        $_SESSION = [];
        session_destroy();
        header('location:home');
    }

    /**
     * @param string $username
     * @param string $password
     * @return int|null
     */
    public static function try_login($username, $password) {
        $link = Db::getInstance();
        $sha_pass = sha1($password);
        $query = "SELECT * FROM users WHERE user_name = :user AND user_password = :pass";
        $stmt = $link->prepare($query);
        $stmt->bindParam(':user', $username);
        $stmt->bindParam(':pass', $sha_pass);
        $stmt->execute();
        if ($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['user_id'];
        } else
            return null;
    }

    public static function token_is_valid($token) {
        $link = Db::getInstance();
        $query = "SELECT * FROM users WHERE pw_recovery_token = :token";
        $stmt = $link->prepare($query);
        $stmt->bindParam(':token', $token);
        $result = $stmt->execute();
        return $result and $stmt->rowCount() > 0;
    }
}