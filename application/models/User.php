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
    public $mail;
    public $access_level;   //0: non loggato
    
    
    /**
     * Main constructor
     * @param int $id
     * @param string $name
     * @param string $mail
     **/
    public function __construct($id, $name, $mail, $access)
    {
        $this->id = $id;
        $this->name = $name;
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
        return '';
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
        return new User($user['user_id'], $user['user_name'], $user['user_mail'], $user['user_access']);
    }

    /**
     * Ottiene l'oggetto utente attualmente collegato
     * @return User
     */
    public static function getCurrent() {
        if(isset($_SESSION['user_id'])) {
            return self::get_user($_SESSION['user_id']);
        } else {
            return new User(0,'', '', 0);
        }
    }
}