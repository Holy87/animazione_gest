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

    public static function changePhone() {
        $user = User::getCurrent();
        if($user->id != intval($_POST['id']) && $user->access_level < 2)
            return json_encode(['ok' => false, 'reason' => 'Non hai i diritti per modificare il telefono.', 'code' => -3]);
        $link = Db::getInstance();
        $query = "UPDATE users SET user_phone = :phone WHERE user_id = :id";
        $stmt = $link->prepare($query);
        $stmt->bindParam(':phone', $_POST['phone']);
        $stmt->bindParam(':id', $_POST['id']);
        if($stmt->execute())
            return json_encode(['ok' => true]);
        else
            return json_encode(['ok' => false, 'reason' => $link->errorInfo(), 'code' => $link->errorCode()]);
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
        $phone = $_POST['phone'];
        $response = User::create($user, $name, $mail, $pass, $role, $phone);
        return json_encode($response);
    }

    public static function edit_user() {

        if(!User::getCurrent()->can_create_users())
        {
            return json_encode(['ok' => false, 'reason' => 'Diritti insufficienti']);
        }
        $user = User::get_user($_POST['id']);
        if(isset($_POST['username'])) {
            $user->name = $_POST['username'];
        }
        $mail = $_POST['mail'];
        $pass = $_POST['password'];
        $role = $_POST['role'];
        $name = $_POST['userfriendly'];
        $tel = $_POST['phone'];
        if(isset($_POST['username']))
        $user->mail = $mail;
        $user->access_level = $role;
        $user->phone = $tel;
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

    public static function get_avaiable_animators() {
        $date = $_GET['date'];
        $outp = [];
        if(User::getCurrent()->access_level > 0) {
            $query = "SELECT * FROM users
                      WHERE users.user_id NOT IN(
                        SELECT user_id FROM animatori_party INNER JOIN feste ON animatori_party.party_id = feste.party_id
                        WHERE feste.data = :date)
                       ORDER BY users.user_friendlyname";
            $link = Db::getInstance();
            $stmt = $link->prepare($query);
            $stmt->bindParam(':date', $date);
            if($stmt->execute()) {
                $result = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
                foreach($result as $user_id) {
                    $user = User::get_user($user_id);
                    $outp[] = ['id' => $user->id, 'name' => $user->friendly_name, 'user' => $user->name, 'picture' => $user->get_avatar_url()];
                }
            } else
                return json_encode(['ok' => false, 'code' => $stmt->errorCode(), 'reason' => $stmt->errorInfo()]);
        }
        return json_encode(['ok' => true, 'data' => $outp]);
    }

    /**
     * Aggiorna la versione utente
     * @return string
     */
    public static function update_user_version() {
        return json_encode(User::getCurrent()->update_last_version());
    }

    public static function request_recovery_password() {
        if(self::ask_google_recapcha()) {
            $user_id = self::getUserIdFromMail($_POST['mail']);
            if($user_id == null)
                return json_encode(['ok'=>false,'reason'=>'Non esiste un utente con questa email.','code'=>0]);
            $token = self::create_reset_token($user_id);
            if($token == null)
                return json_encode(['ok'=>false,'reason'=>'Errore nella procedura di recupero.','code'=>1]);
            $params = ['recipients' => $_POST['mail'], 'token' => $token];
            return json_encode(MailController::send(PW_RECOVERY_MAIL, $params));
        } else
            return json_encode(['ok'=>false, 'code'=>999,'reason'=>'Recapcha non valido.']);
    }

    /**
     * Determina se il recapcha di Google è valido
     * @return bool
     */
    public static function ask_google_recapcha() {
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = array(
            'secret' => '6LcMZTEUAAAAALUbXAxC1_Dr10n3w-jWjSb6iUCS',
            'response' => $_POST['g-recaptcha-response'],
            'remoteip' => $_SERVER['REMOTE_ADDR']
        );
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $response = json_decode($result);
        return $response->success;
    }

    /**
     * @param string $mail
     * @return int
     */
    public static function getUserIdFromMail($mail) {
        $link = Db::getInstance();
        $query = 'SELECT user_id FROM users WHERE user_mail = :mail';
        $stmt = $link->prepare($query);
        $stmt->bindParam(':mail', $mail);
        $result = $stmt->execute();
        if($stmt->rowCount() > 0 and $result) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['user_id'];
        } else
            return null;
    }

    public static function create_reset_token($user_id) {
        $user = User::get_user($user_id);
        if ($user == null)
            return null;
        $token = $user->create_pw_token();
        if($token['ok'])
            return $token['token'];
        else
            return null;
    }
}