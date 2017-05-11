<?php

/**
 * Created by PhpStorm.
 * User: frbos
 * Date: 09/05/2017
 * Time: 22:36
 */
class Party
{
    public $date;
    public $time;
    public $party_id;
    public $theme_id;
    public $customer;
    public $address;

    /**
     * Party constructor.
     * @param $id
     * @param $theme
     * @param $date
     * @param $time
     * @param $customer
     * @param $address
     * @param $creator
     */
    public function __construct($id, $theme, $date, $time, $customer, $address, $creator) {
        $this->party_id = $id;
        $this->theme_id = $theme;
        $this->date = $date;
        $this->time = $time;
        $this->customer = $customer;
        $this->address = $address;
        $this->creator = $creator;
    }

    public function get_animators() {
        $list = [];
        $link = Db::getInstance();
        $query = 'SELECT users.user_id 
                  FROM users INNER JOIN animatori_party ON users.user_id = animatori_party.user_id
                  WHERE animatori_party.party_id = :id';
        $stmt = $link->prepare($query);
        $stmt->bindParam(':id', $this->party_id);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($rows as $row) {
            array_push($list, User::get_user($row['user_id']));
        }
        return $list;
    }

    /**
     * @param User $user
     */
    public function add_animator($user) {
       $link = Db::getInstance();
       $query = 'INSERT INTO animatori_party (user_id, party_id) VALUES (:uid, :pid) ';
       $stmt = $link->prepare($query);
       $stmt->bindParam(':uid', $user->id);
       $stmt->bindParam(':pid', $this->party_id);
        try {
            $link->beginTransaction();
            $stmt->execute();
            $link->commit();
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            $link->rollBack();
            return false;
        }
    }

    /**
     * @param User $user
     */
    public function remove_animator($user) {
        $link = Db::getInstance();
        $query = 'DELETE FROM animatori_party WHERE user_id = :uid and party_id = :pid';
        $stmt = $link->prepare($query);
        $stmt->bindParam(':uid', $user->id);
        $stmt->bindParam(':pid', $this->party_id);
        try {
            $link->beginTransaction();
            $stmt->execute();
            $link->commit();
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            $link->rollBack();
            return false;
        }
    }

    /**
     * @return array<Item>
     */
    public function get_items() {
        $list = [];
        $link = Db::getInstance();
        $query = 'SELECT inventario.item_id, oggetti_party.item_number FROM inventario INNER JOIN oggetti_party ON inventario.item_id = oggetti_party.item_id WHERE oggetti_party.party_id = :id';
        $stmt = $link->prepare($query);
        $stmt->bindParam(':id', $this->party_id);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($rows as $row) {
            array_push($list, Item::get_item($row['item_id']));
        }
        return $list;
    }

    /**
     * @param Item $item
     * @param int $item_number
     */
    public function add_item($item, $item_number = 1) {
        $link = Db::getInstance();
        $query = 'INSERT INTO oggetti_party (item_id, party_id, item_number) VALUES (:iid, :tid, :num)';
        $stmt = $link->prepare($query);
        $stmt->bindParam(':iid', $item->id);
        $stmt->bindParam(':tid', $this->party_id);
        $stmt->bindParam(':num', $item_number);
        $stmt->execute();
    }

    public function delete_item($item) {
        $link = Db::getInstance();
        $query = 'DELETE FROM oggetti_party WHERE item_id = :iid AND party_id = :tid';
        $stmt = $link->prepare($query);
        $stmt->bindParam(':iid', $item->id);
        $stmt->bindParam(':tid', $this->party_id);
        $stmt->execute();
    }

    /**
     * @param $item_id
     * @return int
     */
    public function get_item_number($item_id) {
        $link = Db::getInstance();
        $query = 'SELECT item_number from oggetti_party WHERE item_id = :iid AND party_id = :tid';
        $stmt = $link->prepare($query);
        $stmt->bindParam(':iid', $item_id);
        $stmt->bindParam(':tid', $this->party_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC)['item_number'];
    }

    /**
     * Aggiorna il numero di oggetti
     * @param Item $item
     * @param int $number
     */
    public function change_item_number($item, $number) {
        $link = Db::getInstance();
        $query = 'UPDATE oggetti_party SET item_number = :num WHERE party_id = :tid and item_id = :iid';
        $stmt = $link->prepare($query);
        $stmt->bindParam(':iid', $item->id);
        $stmt->bindParam(':tid', $this->party_id);
        $stmt->bindParam(':num', $number);
        $stmt->execute();
    }

    /**
     * Restituisce l'oggetto tema attuale
     * @return PartyTheme
     */
    public function get_theme() {
        return PartyTheme::getTheme($this->theme_id);
    }

    public function is_done() {
        // TODO: Se data è minore di data attuale
    }

    /**
     * @return array
     */
    public static function get_all() {
        $link = Db::getInstance();
        $query = 'SELECT * FROM feste';
        $list = [];
        $stmt = $link->prepare($query);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($rows as $row) {
            $elem = new Party($row['party_id'], $row['tema'], $row['data'], $row['time'], $row['cliente'], $row['indirizzo'], $row['creatore']);
            array_push($list, $elem);
        }
        return $list;
    }

    /**
     * Ottiene la festa dal DB
     * @param int $party_id
     * @return Party
     */
    public static function get_party($party_id) {
        $link = Db::getInstance();
        $query = 'SELECT * FROM feste where party_id = :id';
        $stmt = $link->prepare($query);
        $stmt->bindParam(':id', $party_id);
        try {
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return new Party($row['party_id'], $row['tema'], $row['data'], $row['time'], $row['cliente'], $row['indirizzo'], $row['creatore']);
        } catch (PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }

    /**
     * Crea nel DB una festa
     * @param integer $customer_id
     * @param string $address
     * @param integer $theme_id
     * @param DateTime $date
     * @param DateTime $time
     * @param float $price
     */
    public static function create($customer_id, $address, $theme_id, $date, $time, $price) {
        $user = User::getCurrent();
        if ($user->access_level < 2) {

        }
        $link = Db::getInstance();
        $query = 'INSERT INTO feste (cliente, indirizzo, data, creatore, prezzo, tema, ora)
                  VALUES (:customer, :address, :dat, :creator, :price, :theme, :hou)';
        $stmt = $link->prepare($query);
        $stmt->bindParam(':customer', $customer_id);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':theme', $theme_id);
        $stmt->bindParam(':dat', $date);
        $stmt->bindParam(':hou', $time);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':creator', User::getCurrent()->id);

        try {
            $link->beginTransaction();
            $stmt->execute();
            $link->commit();
            return self::get_party($link->lastInsertId());
        } catch (PDOException $e) {
            $link->rollBack();
            echo $e->getMessage();
            return null;
        }
    }
}