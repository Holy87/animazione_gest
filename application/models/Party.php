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
    public $price;

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
    public function __construct($id, $theme, $date, $time, $customer, $address, $creator, $price) {
        $this->party_id = $id;
        $this->theme_id = $theme;
        $this->date = DateTime::createFromFormat('Y-m-d', $date);
        $this->time = DateTime::createFromFormat('H:i:s', $time);
        $this->customer = $customer;
        $this->address = $address;
        $this->creator = $creator;
        $this->price = $price;
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
        if(!$this->animator_present($user)) {
            $link = Db::getInstance();
            $query = 'INSERT INTO animatori_party (user_id, party_id) VALUES (:uid, :pid) ';
            $stmt = $link->prepare($query);
            $stmt->bindParam(':uid', $user->id);
            $stmt->bindParam(':pid', $this->party_id);
            if($stmt->execute())
                return ['ok' => true];
            else
                return ['ok' => false, 'reason' => $stmt->errorInfo(), 'code' => $stmt->errorCode()];
        }
        else
            return ['ok' => false, 'reason' => 'Animatore già presente', 'code' => 1];
    }

    /**
     * @param User $user
     */
    public function animator_present($user) {
        $link = Db::getInstance();
        $query = "SELECT * FROM animatori_party WHERE party_id = :pid AND user_id = :uid";
        $stmt = $link->prepare($query);
        $stmt->bindParam(':pid', $this->party_id);
        $stmt->bindParam(':uid', $user->id);
        $result = $stmt->execute();
        return $result == true and $stmt->rowCount() > 0;
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
        if($stmt->execute())
            return ['ok' => true];
        else
            return ['ok' => false, 'reason' => $stmt->errorInfo(), 'code' => $stmt->errorCode()];
    }

    public function get_animators_names() {
        $names = [];
        /** @var User $animator */
        foreach($this->get_animators() as $animator) {
            array_push($names, $animator->friendly_name);
        }
        return implode(", ", $names);
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
        if($stmt->execute())
            return ['ok' => true];
        else
            return ['ok' => false, 'reason' => $stmt->errorInfo(), 'code' => $stmt->errorCode()];
    }

    public function delete_item($item) {
        $link = Db::getInstance();
        $query = 'DELETE FROM oggetti_party WHERE item_id = :iid AND party_id = :tid';
        $stmt = $link->prepare($query);
        $stmt->bindParam(':iid', $item->id);
        $stmt->bindParam(':tid', $this->party_id);
        if($stmt->execute())
            return ['ok' => true];
        else
            return ['ok' => false, 'reason' => $stmt->errorInfo(), 'code' => $stmt->errorCode()];
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
        if($stmt->execute())
            return ['ok' => true];
        else
            return ['ok' => false, 'reason' => $stmt->errorInfo(), 'code' => $stmt->errorCode()];
    }

    /**
     * Restituisce l'oggetto tema attuale
     * @return PartyTheme
     */
    public function get_theme() {
        return PartyTheme::getTheme($this->theme_id);
    }

    /**
     * Restituisce la data come stringa
     * @return false|string
     */
    public function get_printable_date() {
        return date_format($this->date, "d-m-Y");
    }

    /**
     * Restituisce l'ora come stringa
     * @return false|string
     */
    public function get_printable_hour() {
        return date_format($this->time, "H:i");
    }

    public function set_date($date_string) {
        $this->date = strtotime($date_string);
    }

    public function is_done() {
        return date_default_timezone_get() > $this->date;
    }

    public function save() {
        $query = "UPDATE feste SET cliente = :cust, indirizzo = :addr, data = :date, prezzo = :price, theme_id = :theme
                  WHERE party_id = :id";
        $link = Db::getInstance();
        $stmt = $link->prepare($query);
        $stmt->bindParam(':cust', $this->customer);
        $stmt->bindParam(':addr', $this->address);
        $stmt->bindParam(':date', $this->date);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':theme', $this->theme_id);
        $stmt->bindParam(':id', $this->party_id);
        if($stmt->execute())
            return ['ok' => true];
        else
            return ['ok' => false, 'reason' => $stmt->errorInfo(), 'code' => $stmt->errorCode()];
    }

    public function delete() {
        $link = Db::getInstance();
        $query = 'DELETE from feste WHERE party_id = :id';
        $stmt = $link->prepare($query);
        $stmt->bindParam(':id', $this->party_id);
        if($stmt->execute())
            return ['ok' => true];
        else
            return ['ok' => false, 'reason' => $stmt->errorInfo(), 'code' => $stmt->errorCode()];
    }

    /**
     * @return array
     */
    public static function get_all() {
        $link = Db::getInstance();
        $user = User::getCurrent();
        if ($user->access_level > 1)
            $query = 'SELECT * FROM feste';
        else
            $query = 'SELECT * FROM feste INNER JOIN animatori_party ON feste.party_id = animatori_party.party_id
                      WHERE animatori_party.user_id = :id';
        $list = [];
        $stmt = $link->prepare($query);
        if($user->access_level < 2){$stmt->bindParam(':id', $user->id);}
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($rows as $row) {
            $elem = new Party($row['party_id'], $row['theme_id'], $row['data'], $row['ora'], $row['cliente'], $row['indirizzo'], $row['creatore'], $row['prezzo']);
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
        if($party_id == 0)
            return new Party(0, null, null, null, '', '', null, 0);
        $link = Db::getInstance();
        $query = 'SELECT * FROM feste where party_id = :id';
        $stmt = $link->prepare($query);
        $stmt->bindParam(':id', $party_id);
        try {
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return new Party($row['party_id'], $row['theme_id'], $row['data'], $row['ora'], $row['cliente'], $row['indirizzo'], $row['creatore'], $row['prezzo']);
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
        $link = Db::getInstance();
        $query = 'INSERT INTO feste (cliente, indirizzo, data, creatore, prezzo, theme_id, ora)
                  VALUES (:customer, :address, :dat, :creator, :price, :theme, :hou)';
        $stmt = $link->prepare($query);
        $stmt->bindParam(':customer', $customer_id);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':theme', $theme_id);
        $stmt->bindParam(':dat', $date);
        $stmt->bindParam(':hou', $time);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':creator', $user->id);
        if($stmt->execute())
            return ['ok' => true];
        else
            return ['ok' => false, 'reason' => $stmt->errorInfo(), 'code' => $stmt->errorCode()];
    }
}