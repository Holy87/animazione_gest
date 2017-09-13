<?php

/**
 * Created by PhpStorm.
 * User: frbos
 * Date: 09/05/2017
 * Time: 22:36
 */
class Party
{
    private $date;
    private $time;
    public $party_id;
    public $theme_id;
    public $customer;
    public $address;
    public $price;
    public $notes;
    public $phone;
    public $hours;
    public $guest_of_honor;
    public $guest_age;
    public $generic_age;
    public $child_number;
    public $fuel;
    private $creator;

    /**
     * Party constructor.
     * @param array $row
     */
    public function __construct($row) {
        $this->party_id = $row['party_id'];
        $this->theme_id = $row['theme_id'];
        $this->date = DateTime::createFromFormat('Y-m-d', $row['data']);
        $this->time = DateTime::createFromFormat('H:i:s', $row['ora']);
        $this->customer = $row['cliente'];
        $this->address = $row['indirizzo'];
        $this->creator = $row['creatore'];
        $this->price = $row['prezzo'];
        $this->notes = $row['note'];
        $this->phone = $row['telefono'];
        $this->hours = $row['ore'];
        $this->guest_of_honor = $row['festeggiato'];
        $this->guest_age = $row['eta_festeggiato'];
        $this->generic_age = $row['eta_media'];
        $this->child_number = $row['numero_bambini'];
        $this->fuel = $row['carburante'];
    }

    /**
     * @return array
     */
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
     * @return array
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
     * @return bool
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
     * @return array
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

    /**
     * @return string
     */
    public function get_animators_names() {
        $names = [];
        /** @var User $animator */
        foreach($this->get_animators() as $animator) {
            array_push($names, $animator->friendly_name);
        }
        return implode(", ", $names);
    }

    public function set_date($date_string) {
        //$this->date = date_create('Y-m-d', str_replace('/','-',$date_string));
        $this->date = date_create(str_replace('/','-',$date_string));
    }

    public function set_time($time_string) {
        $this->time = date_create(str_replace('.',':',$time_string));
    }

    /**
     * @return DateTime
     */
    public function get_date() {
        return $this->date;
    }

    /**
     * @return DateTime
     */
    public function get_time() {
        return $this->time;
    }

    /**
     * Restituisce tutti gli oggetti della festa
     * @return array<Item>
     */
    public function get_items() {
        return $this->get_all_items();
    }

    /**
     * Restituisce solo gli oggetti della festa
     * @return array<Item>
     */
    public function get_own_items() {
        $query = 'SELECT item_id
                FROM oggetti_party
                WHERE party_id = :pid';
        $link = Db::getInstance();
        $stmt = $link->prepare($query);
        $stmt->bindParam(':pid', $this->party_id);
        $stmt->bindParam(':tid', $this->theme_id);
        $result = $stmt->execute();
        if($result) {
            return $this->get_items_array($stmt->fetchAll(PDO::FETCH_COLUMN, 0));
        } else
            return [];
    }

    /**
     * Restituisce solo gli oggetti del tema
     * @return array<Item>
     */
    public function get_theme_items() {
        return $this->get_theme()->get_items();
    }

    /**
     * @return User
     */
    public function get_creator() {
        return User::get_user($this->creator);
    }

    /**
     * Restituisce tutti gli oggetti della festa
     * @return array<Item>
     */
    public function get_all_items() {
        $query = 'SELECT item_id
                FROM oggetti_party
                WHERE party_id = :pid
                UNION SELECT item_id
                FROM oggetti_temi
                WHERE theme_id = :tid';
        $link = Db::getInstance();
        $stmt = $link->prepare($query);
        $stmt->bindParam(':pid', $this->party_id);
        $stmt->bindParam(':tid', $this->theme_id);
        $result = $stmt->execute();
        if($result) {
            return $this->get_items_array($stmt->fetchAll(PDO::FETCH_COLUMN, 0));
        } else
            return [];
    }

    /**
     * Restituisce l'array degli oggetti da un array di ID
     * @param array $items_id
     * @return array
     */
    private function get_items_array($items_id) {
        $items = [];
        foreach($items_id as $id) {
            $items[] = Item::get_item($id);
        }
        return $items;
    }

    /**
     * @param Item $item
     * @param int $item_number
     * @return array
     */
    public function add_item($item, $item_number = 1) {
        if ($this->has_item($item))
            return $this->change_item_number($item, $this->get_item_number($item->id) + $item_number);
        else
            return $this->insert_item($item, $item_number);
    }

    public function insert_item($item, $item_number = 1) {
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

    /**
     * @param Item $item
     * @return array
     */
    public function delete_item($item) {
        return $this->delete_item_from_id($item->id);
    }

    /**
     * @param int $item_id
     * @return array
     */
    public function delete_item_from_id($item_id) {
        $link = Db::getInstance();
        $query = 'DELETE FROM oggetti_party WHERE item_id = :iid AND party_id = :tid';
        $stmt = $link->prepare($query);
        $stmt->bindParam(':iid', $item_id);
        $stmt->bindParam(':tid', $this->party_id);
        if($stmt->execute())
            return ['ok' => true];
        else
            return ['ok' => false, 'reason' => $stmt->errorInfo(), 'code' => $stmt->errorCode()];
    }

    /**
     * @param Item $item
     * @return bool
     */
    public function has_item($item) {
        return $this->has_item_id($item->id);
    }

    /**
     * @param int $item_id
     * @return bool
     */
    public function has_item_id($item_id) {
        return $this->get_own_item_number($item_id) > 0;
    }

    /**
     * @param $item_id
     * @return int
     */
    public function get_own_item_number($item_id) {
        $link = Db::getInstance();
        $query = 'SELECT item_number from oggetti_party WHERE item_id = :iid AND party_id = :pid';
        $stmt = $link->prepare($query);
        $stmt->bindParam(':iid', $item_id);
        $stmt->bindParam(':pid', $this->party_id);
        $stmt->execute();
        if($stmt->rowCount() > 0)
            return $stmt->fetch(PDO::FETCH_ASSOC)['item_number'];
        else
            return 0;
    }

    /**
     * Ottiene il numero
     * @param $item_id
     * @return int
     */
    public function get_theme_item_number($item_id) {
        $theme = $this->get_theme();
        if($theme != null)
            return $theme->get_item_number($item_id);
        else
            return 0;
    }

    /**
     * Ottiene il numero totale di un oggetto sommando quelli del tema e quelli aggiunti alla festa
     * @param int $item_id
     * @return int
     */
    public function get_item_number($item_id) {
        return $this->get_own_item_number($item_id) + $this->get_theme_item_number($item_id);
    }

    /**
     * Aggiorna il numero di oggetti
     * @param Item $item
     * @param int $number
     * @return array
     */
    public function change_item_number($item, $number) {
        return $this->change_item_number_from_id($item->id, $number);
    }

    /**
     * Aggiorna il numero di oggetti
     * @param int $item_id
     * @param int $number
     * @return array
     */
    public function change_item_number_from_id($item_id, $number) {
        $item = Item::get_item($item_id);
        if ($number <= 0)
            return $this->delete_item($item);
        if (!$this->has_item($item))
            return $this->add_item($item, $number);
        $link = Db::getInstance();
        $query = 'UPDATE oggetti_party SET item_number = :num WHERE party_id = :tid and item_id = :iid';
        $stmt = $link->prepare($query);
        $stmt->bindParam(':iid', $item_id);
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
        if($this->theme_id == null)
            return null;
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

    /**
     * Determina se la festa è finita
     * @return bool
     */
    public function is_done() {
        return new DateTime() > $this->get_end_date();
    }

    /**
     * Restituisce data e ora di fine della festa
     * @return DateTime
     */
    public function get_end_date() {
        $date = $this->get_date_time();
        $date->add(new DateInterval('PT'.$this->hours.'H'));
        return $date;
    }

    /**
     * Restituisce data e ora dell'inizio della festa
     * @return DateTime
     */
    public function get_date_time() {
        $time = new DateTime();
        $year = intval($this->date->format('Y'));
        $month = intval($this->date->format('m'));
        $day = intval($this->date->format('d'));
        $hour = intval($this->time->format('H'));
        $minute = intval($this->time->format('i'));
        $time->setDate($year, $month, $day);
        $time->setTime($hour, $minute, 0);
        return $time;
    }

    public function save() {
        $query = "UPDATE feste SET cliente = :cust, indirizzo = :addr, data = :date, prezzo = :price, theme_id = :theme, ora = :hour,
                  festeggiato = :guest, eta_festeggiato = :guest_age, eta_media = :mid_age, numero_bambini = :children, ore = :hours,
                  note = :notes, carburante = :fuel, telefono = :phone
                  WHERE party_id = :id";
        $link = Db::getInstance();
        $stmt = $link->prepare($query);
        $date = $this->date->format('Y-m-d');
        $hour = $this->time->format('H:i:s');
        //return ['ok' => false, 'reason' => $date, 'code' => 0];
        $stmt->bindParam(':cust', $this->customer);
        $stmt->bindParam(':addr', $this->address);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':hour', $hour);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':theme', $this->theme_id);
        $stmt->bindParam(':id', $this->party_id);
        $stmt->bindParam(':guest', $this->guest_of_honor);
        $stmt->bindParam(':guest_age', $this->guest_age);
        $stmt->bindParam(':mid_age', $this->generic_age);
        $stmt->bindParam(':children', $this->child_number);
        $stmt->bindParam(':hours', $this->hours);
        $stmt->bindParam(':notes', $this->notes);
        $stmt->bindParam(':fuel', $this->fuel);
        $stmt->bindParam(':phone', $this->phone);
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
            $elem = new Party($row);
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
            return self::empty_party();
        $link = Db::getInstance();
        $query = 'SELECT * FROM feste where party_id = :id';
        $stmt = $link->prepare($query);
        $stmt->bindParam(':id', $party_id);
        try {
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return new Party($row);
        } catch (PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }

    public static function empty_party() {
        $row = [];
        $row['party_id'] = 0;
        $row['theme_id'] = null;
        $row['data'] = date("Y-m-d", time());
        $row['ora'] = date("H:i:s", time());
        $row['cliente'] = '';
        $row['indirizzo'] = '';
        $row['creatore'] = null;
        $row['prezzo'] = 0;
        $row['festeggiato'] = '';
        $row['eta_festeggiato'] = null;
        $row['eta_media'] = null;
        $row['numero_bambini'] = null;
        $row['note'] = '';
        $row['carburante'] = null;
        $row['telefono'] = '';
        $row['ore'] = 2;
        return new Party($row);
    }

    public static function dummy_date() {


    }

    /**
     * Crea nel DB una festa. Restituisce array con errore o ID di inserimento
     * @param integer $customer_id
     * @param string $address
     * @param integer $theme_id
     * @param DateTime $date
     * @param DateTime $time
     * @param float $price
     * @param $guest
     * @param $guest_age
     * @param $mid_age
     * @param $children
     * @param $hours
     * @param $notes
     * @param $fuel
     * @param $phone
     * @return array
     */
    public static function create($customer_id, $address, $theme_id, $date, $time, $price, $guest, $guest_age, $mid_age,
            $children, $hours, $notes, $fuel, $phone) {
        $user = User::getCurrent();
        $link = Db::getInstance();
        $query = 'INSERT INTO feste (cliente, indirizzo, data, creatore, prezzo, theme_id, ora, festeggiato, eta_festeggiato, eta_media, numero_bambini, ore, note, carburante, telefono)
                  VALUES (:customer, :address, :dat, :creator, :price, :theme, :hou, :guest, :guest_age, :mid_age, :children, :hours, :notes, :fuel, :phone)';
        $stmt = $link->prepare($query);
        $stmt->bindParam(':customer', $customer_id);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':theme', $theme_id);
        $stmt->bindParam(':dat', $date);
        $stmt->bindParam(':hou', $time);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':creator', $user->id);
        $stmt->bindParam(':guest', $guest);
        $stmt->bindParam(':guest_age', $guest_age);
        $stmt->bindParam(':mid_age', $mid_age);
        $stmt->bindParam(':children', $children);
        $stmt->bindParam(':hours', $hours);
        $stmt->bindParam(':notes', $notes);
        $stmt->bindParam(':fuel', $fuel);
        $stmt->bindParam(':phone', $phone);
        if($stmt->execute())
            return ['ok' => true, 'id' => $link->lastInsertId()];
        else
            return ['ok' => false, 'reason' => $stmt->errorInfo(), 'code' => $stmt->errorCode()];
    }
}