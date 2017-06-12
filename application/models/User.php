<?php

/**
 * Created by PhpStorm.
 * User: Francesco
 * Date: 21/03/2017
 * Time: 21:12
 */
class User
{
    public $id;
    public $name;
    public $friendly_name;
    public $mail;
    public $access_level;   //0: non loggato
    
    
    /**
     * Main constructor
     * @param int $id
     * @param string $name
     * @param string $mail
     **/
    public function __construct($id, $name, $friendly_name, $mail, $access)
    {
        $this->id = $id;
        $this->name = $name;
        $this->friendly_name = $friendly_name;
        $this->mail = $mail;
        $this->access_level = $access;
    }

    /**
     * Determina se l'utente può vedere tutti gli eventi
     * @return bool
     */
    public function can_view_all_events() {
        return $this->access_level > 1;
    }

    /**
     * Restituisce l'URL dell'Avatar
     * @return string
     */
    public function get_avatar_url() {
        $images = glob("./uploads/image-profiles/".$this->generateFileName().".*");
        if (count($images) > 0)
            return $images[0];
        else
            return 'assets/images/profile-placeholder.jpg';
    }

    /**
     * Determina se l'utente può modificare gli eventi
     * @return bool
     */
    public function can_edit_events() {
        return $this->access_level > 1;
    }

    /**
     * Determina se l'utente può creare o modificare altri utenti
     * @return bool
     */
    public function can_create_users() {
        return $this->access_level > 2;
    }

    /**
     * @return array
     */
    public function save() {
        $link = Db::getInstance();
        $query = "UPDATE users SET
        user_mail = :mail,
        user_access = :access,
        user_friendlyname = :name,
        user_name = :nick
        WHERE user_id = :id";
        $stmt = $link->prepare($query);
        $stmt->bindParam(':mail', $this->mail);
        $stmt->bindParam(':access', $this->access_level);
        $stmt->bindParam(':name', $this->friendly_name);
        $stmt->bindParam(':nick', $this->name);
        $stmt->bindParam(':id', $this->id);
        if($stmt->execute())
        {
            return ['ok' => true];
        } else
            return ['ok' => false, 'reason' => $stmt->errorInfo(), 'code' => $stmt->errorCode()];
    }

    public function change_friendly_name($new_name) {
        $this->friendly_name = $new_name;
        $link = Db::getInstance();
        $query = "UPDATE users SET
        user_friendlyname = :name
        WHERE user_id = :id";
        $stmt = $link->prepare($query);
        $stmt->bindParam(':name', $this->friendly_name);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
        //return $stmt->rowCount() > 0;
    }

    /**
     * @param string $old_password
     * @param string $new_password
     * @return bool
     */
    public function change_password_safe($old_password, $new_password) {
        $sha1_new_password = sha1($new_password);
        $sha1_old_password = sha1($old_password);
        $link = Db::getInstance();
        $query = "UPDATE users SET user_password = :npass
        WHERE user_password = :opass AND user_id = :id";
        $stmt = $link->prepare($query);
        $stmt->bindParam(':npass', $sha1_new_password);
        $stmt->bindParam(':opass', $sha1_old_password);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    /**
     * @param string $new_password
     * @return bool
     */
    public function change_password_unsafe($new_password) {
        $sha1_new_password = sha1($new_password);
        $link = Db::getInstance();
        $query = "UPDATE users SET user_password = :npass
        WHERE user_id = :id";
        $stmt = $link->prepare($query);
        $stmt->bindParam(':npass', $sha1_new_password);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    /**
     * Ottiene l'oggetto utente
     * @return User
     */
    public static function get_user($id) {
        $link = Db::getInstance();
        $query = "SELECT * FROM users WHERE user_id = :id";
        $stmt = $link->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return new User($user['user_id'], $user['user_name'], $user['user_friendlyname'], $user['user_mail'], $user['user_access']);
    }

    /**
     * Ottiene l'oggetto utente attualmente collegato
     * @return User
     */
    public static function getCurrent() {
        if(isset($_SESSION['user_id'])) {
            return self::get_user($_SESSION['user_id']);
        } else {
            return new User(0,'guest', 'Ospite', '', 0);
        }
    }

    /**
     * @return array
     */
    public static function get_all() {
        $items = [];
        $link = Db::getInstance();
        $query = "SELECT * FROM users";
        $stmt = $link->prepare($query);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($rows as $row)
        {
            $items[] = new User($row['user_id'], $row['user_name'], $row['user_friendlyname'], $row['user_mail'], $row['user_access']);
        }
        return $items;
    }

    /**
     * @return string
     */
    public function generateFileName() {
        return 'avatar-'.$this->id;
    }

    /**
     * @return string
     */
    public function group_name() {
        switch ($this->access_level) {
            case 1:
                return "Animatore";
            case 2:
                return "Segreteria";
            case 3:
                return "Amministratore";
            default:
                return "Bloccato";
        }
    }

    /**
     * @param $user
     * @param $name
     * @param $mail
     * @param $password
     * @param $access_level
     * @return array
     */
    public static function create($user, $name, $mail, $password, $access_level) {
        $pass = sha1($password);
        $query = "INSERT INTO users (user_name, user_mail, user_access, user_password, user_friendlyname) VALUES (:user, :mail, :access, :pass, :name)";
        $link = Db::getInstance();
        $stmt = $link->prepare($query);
        $stmt->bindParam(':user', $user);
        $stmt->bindParam(':mail', $mail);
        $stmt->bindParam(':pass', $pass);
        $stmt->bindParam(':access', $access_level);
        $stmt->bindParam(':name', $name);
        if ($stmt->execute())
            return ['id' => $link->lastInsertId('user_id'), 'ok' => true];
        else
            return ['ok' => false, 'code' => $stmt->errorCode(), 'reason' => $stmt->errorInfo()];
    }
}