<?php

/**
 * Created by PhpStorm.
 * User: Gold Service
 * Date: 10/05/2017
 * Time: 12:06
 */
class PartyTheme
{
    public $id;
    public $name;
    public $price;
    public $description;

    /**
     * PartyTheme constructor.
     * @param int $id
     * @param string $name
     * @param float $price
     */
    public function __construct($id, $name, $price, $description)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->description = $description;
    }

    /**
     * @return array<Item>
     */
    public function get_items() {
        $list = [];
        $link = Db::getInstance();
        $query = 'SELECT inventario.item_id, oggetti_temi.item_number FROM inventario INNER JOIN oggetti_temi ON inventario.item_id = oggetti_temi.item_id WHERE oggetti_temi.theme_id = :id';
        $stmt = $link->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($rows as $row) {
            array_push($list, Item::get_item($row['item_id']));
        }
        return $list;
    }

    /**
     * @return int
     */
    public function intem_number() {
        return count($this->get_items());
    }

    /**
     * @param Item $item
     */
    function item_name($item) {
        return $item->name;
    }

    public function item_names() {
        $items = $this->get_items();
        $array = array_map(array($this, "item_name"), $items);
        return implode(', ', $array);
    }

    /**
     * @param Item $item
     * @param int $item_number
     * @return bool
     */
    public function add_item($item, $item_number = 1) {
        $link = Db::getInstance();
        $query = 'INSERT INTO oggetti_temi (item_id, theme_id, item_number) VALUES (:iid, :tid, :num)';
        $stmt = $link->prepare($query);
        $stmt->bindParam(':iid', $item->id);
        $stmt->bindParam(':tid', $this->id);
        $stmt->bindParam(':num', $item_number);
        return $stmt->execute();
    }

    /**
     * @param Item $item
     * @return array
     */
    public function delete_item($item) {
        return $this->delete_item_from_id($item->id);
    }

    /**
     * @param Item $item
     * @return array
     */
    public function delete_item_from_id($item_id) {
        $link = Db::getInstance();
        $query = 'DELETE FROM oggetti_temi WHERE item_id = :iid AND theme_id = :tid';
        $stmt = $link->prepare($query);
        $stmt->bindParam(':iid', $item_id);
        $stmt->bindParam(':tid', $this->id);
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
        $query = 'SELECT item_number from oggetti_temi WHERE item_id = :iid AND theme_id = :tid';
        $stmt = $link->prepare($query);
        $stmt->bindParam(':iid', $item_id);
        $stmt->bindParam(':tid', $this->id);
        $stmt->execute();
        if($stmt->rowCount() > 0)
            return $stmt->fetch(PDO::FETCH_ASSOC)['item_number'];
        else
            return 0;
    }

    public function has_item($item_id) {
        return $this->get_item_number($item_id) > 0;
    }

    /**
     * Aggiorna il numero di oggetti
     * @param Item $item
     * @param int $number
     */
    public function change_item_number($item, $number) {
        if($number <= 0) {
            $this->delete_item($item);
            return;
        }
        $link = Db::getInstance();
        $query = 'UPDATE oggetti_temi SET item_number = :num WHERE theme_id = :tid and item_id = :iid';
        $stmt = $link->prepare($query);
        $stmt->bindParam(':iid', $item->id);
        $stmt->bindParam(':tid', $this->id);
        $stmt->bindParam(':num', $number);
        $stmt->execute();
    }

    /**
     * Salva le modifiche del tema nel database.
     * @return array
     */
    public function save() {
        $link = Db::getInstance();
        $query = 'UPDATE temi SET theme_name = :name, theme_price = :price, theme_description = :desc
                  WHERE theme_id = :id';
        $stmt = $link->prepare($query);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':desc', $this->description);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':id', $this->id);
        try {
            $link->beginTransaction();
            $stmt->execute();
            $link->commit();
            return ['ok' => true];
        } catch (PDOException $e) {
            echo $e->getMessage();
            $link->rollBack();
            return ['ok' => false, 'reason' => $stmt->errorInfo(), 'code' => $stmt->errorCode()];
        }
    }

    /**
     * Cancella un tema dal database
     * @return array
     */
    public function delete() {
        $link = Db::getInstance();
        $query = "DELETE from temi WHERE theme_id = :id";
        $stmt = $link->prepare($query);
        $stmt->bindParam(':id', $this->id);
        if($stmt->execute())
            return ['ok' => true, 'id' => $this->id];
        else
            return ['ok' => false, 'reason' => $stmt->errorInfo(), 'code' => $stmt->errorCode()];
    }

    /**
     * Ottiene tutti i temi
     * @return array<PartyTheme>
     */
    public static function getAllThemes() {
        $link = Db::getInstance();
        $query = 'SELECT * FROM temi';
        $list = [];
        $stmt = $link->prepare($query);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($rows as $row) {
            $elem = new PartyTheme($row['theme_id'], $row['theme_name'], $row['theme_price'], $row['theme_description']);
            array_push($list, $elem);
        }
        return $list;
    }

    /**
     * Ottiene un tema determinato da uno specifico ID
     * @param integer $id
     * @return PartyTheme
     */
    public static function getTheme($id) {
        if($id == null)
            return null;
        $link = Db::getInstance();
        $query = 'SELECT * FROM temi WHERE theme_id = :id';
        $stmt = $link->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        if($stmt->rowCount() > 0) {
            $theme = $stmt->fetch(PDO::FETCH_ASSOC);
            return new PartyTheme($theme['theme_id'], $theme['theme_name'], $theme['theme_price'], $theme['theme_description']);
        } else
            return null;
    }

    public static function create($name, $description, $price) {
        $link = Db::getInstance();
        $query = "INSERT INTO temi (theme_name, theme_description, theme_price) VALUES (:name, :desc, :price)";
        $stmt = $link->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':desc', $description);
        $stmt->bindParam(':price', $price);
        $result = $stmt->execute();
        if($result)
            return ['ok' => true, 'id' => $link->lastInsertId('theme_id')];
        else
            return ['ok' => false, 'reason' => $stmt->errorInfo(), 'code' => $stmt->errorCode()];
    }
}