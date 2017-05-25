<?php

/**
 * Created by PhpStorm.
 * User: Gold Service
 * Date: 18/05/2017
 * Time: 08:02
 */
require_once ABS_PATH."/application/models/User.php";

class UserController
{
    public static function checkMail() {
        if(isset($_POST['user-mail'])) {
            if(isset($_POST['id'])) {
                $link = Db::getInstance();
                $query = "SELECT * FROM users WHERE user_mail = :mail AND user_id <> :id";
                $stmt = $link->prepare($query);
                $stmt->bindParam(':mail', $_POST['user-mail']);
                $stmt->bindParam('id', $_POST['id']);
                $stmt->execute();
                if($stmt->rowCount() > 0) {
                    return json_encode(['ok' => false, 'reason' => "Questa email è già utilizzata su un altro profilo."]);
                } else
                    return json_encode(['ok' => true]);
            } else
                return json_encode(['ok' => false, 'reason' => "ID utente assente"]);
        } else
            return json_encode(['ok' => false, 'reason' => "Campo Email vuoto"]);
    }

    public static function changeMail() {
        $mail_used = json_decode(self::checkMail());
        if ($mail_used->{'ok'} == true)
        {
            $link = Db::getInstance();
            $query = "UPDATE users SET user_mail = :mail WHERE user_id = :id";
            $stmt = $link->prepare($query);
            $stmt->bindParam(':mail', $_POST['user-mail']);
            $stmt->bindParam('id', $_POST['id']);
            $stmt->execute();
            return json_encode(['ok' => true]);
        } else
            return $mail_used;
    }

    public static function changeName() {
        $user = User::get_user($_POST['id']);
        if($user == null) {
            return json_encode(['ok' => false, 'reason' => "Utente non trovato"]);
        }
        if (!$user->can_create_users()) {
            return json_encode(['ok' => false, 'reason' => "Permesso negato"]);
        }
        $result = $user->change_friendly_name($_POST['name']);
        if ($result)
            return json_encode(['ok' => true, 'name' => $_POST['name']]);
        else
            return json_encode(['ok' => false, 'reason' => 'Nome già utilizato']);
    }

    public static function changePassword() {
        $user = User::get_user($_POST['id']);
        if($user) {
            if($user->change_password_safe($_POST["old-password"], $_POST['new-password']))
                return json_encode(['ok' => true]);
            else
                return json_encode(['ok' => false, 'reason' => 'La vecchia password è errata.', 'old' => $_POST["old-password"]]);
        } else {
            return json_encode(['ok' => false, 'reason' => 'Utente inesistente']);
        }
    }

    public static function get_users() {
        $user = User::getCurrent();
        $serialized_users = [];
        if($user->can_create_users()) {
            $users = User::get_all();
            /** @var User $user */
            foreach($users as $user)
            {
                $serialized_users[] = ['id' => $user->id, 'nickname' => $user->name,
                    'name' => $user->friendly_name, 'access' => $user->access_level,
                    'mail' => $user->mail, 'role' => $user->group_name(), 'avatar' => $user->get_avatar_url()];
            }
        }
        return json_encode($serialized_users);
    }

    public static function create_user() {
        if(!User::getCurrent()->can_create_users())
        {
            return json_encode(['ok' => false, 'reason' => 'Diritti insufficienti']);
        }
        $user = $_POST['username'];
        $mail = $_POST['mail'];
        $pass = $_POST['password'];
        $role = $_POST['role'];
        $name = $_POST['userfriendly'];
        $response = User::create($user, $name, $mail, $pass, $role);
        return json_encode($response);
    }

    public static function edit_user() {
        if(!User::getCurrent()->can_create_users())
        {
            return json_encode(['ok' => false, 'reason' => 'Diritti insufficienti']);
        }
        if(isset($_POST['username']))
            $nick = $_POST['username'];
        $mail = $_POST['mail'];
        $pass = $_POST['password'];
        $role = $_POST['role'];
        $name = $_POST['userfriendly'];
        $user = User::get_user($_POST['id']);
        if(isset($_POST['username'])) $user->name = $nick;
        $user->mail = $mail;
        $user->access_level = $role;
        $user->friendly_name = $name;
        if(strlen($pass) > 0) $user->change_password_unsafe($pass);
        return json_encode($user->save());
    }

    public static function delete_user()
    {
        if(!User::getCurrent()->can_create_users())
        {
            return json_encode(['ok' => false, 'reason' => 'Diritti insufficienti']);
        }
        $query = "DELETE FROM users WHERE user_id = :id";
        $user_id = $_POST['id'];// $_GET['user_id'];
        $link = Db::getInstance();
        $stmt = $link->prepare($query);
        $stmt->bindParam(':id', $user_id);
        $result = $stmt->execute();
        return json_encode(['ok' => $result, 'code' => $stmt->errorCode(), 'reason' => $stmt->errorInfo()]);
    }
}