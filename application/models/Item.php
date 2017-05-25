<?php

/**
 * Created by PhpStorm.
 * User: Gold Service
 * Date: 10/05/2017
 * Time: 12:51
 */
class Item
{
    public $id;
    public $name;
    public $number;
    public $ward;
    public $consumable;

    /**
     * Item constructor.
     * @param int $id
     * @param string $name
     * @param int $number
     * @param int $ward
     * @param int $consumable
     */
    public function __construct($id, $name, $number, $ward = 1, $consumable = 0)
    {
        $this->name = $name;
        $this->id = $id;
        $this->number = $number;
        $this->consumable = $consumable;
        $this->ward = $ward;
    }

    /**
     * Salva l'oggetto
     * @return bool
     */
    public function save() {
        $link = Db::getInstance();
        $query = 'UPDATE inventario SET item_name = :name, item_number = :num, item_ward = :ward, item_consumable = :cons
            WHERE item_id = :id';
        $stmt = $link->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':num', $this->number);
        $stmt->bindParam(':ward', $this->ward);
        $stmt->bindParam(':cons', $this->consumable);
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
     * Ottiene l'oggetto da un determinato ID
     * @param $item_id
     * @return Item
     */
    public static function get_item($item_id) {
        $link = Db::getInstance();
        $query = 'SELECT * FROM inventario WHERE item_id = :id';
        $stmt = $link->prepare($query);
        $stmt->bindParam(':id', $item_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return new Item($row['item_id'], $row['item_name'], $row['item_number'], $row['item_ward'], $row['item_consumable']);
    }

    /**
     * @return array<Item>
     */
    public static function get_all() {
        $items = [];
        $link = Db::getInstance();
        $query = 'SELECT item_id, item_name, item_number FROM inventario ORDER BY item_name';
        $stmt = $link->prepare($query);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($rows as $row)
        {
            $items[] = new Item($row['item_id'], $row['item_name'], $row['item_number'], $row['item_ward'], $row['item_consumable']);
        }
        return $items;
    }

    /**
     * Cancella l'oggetto dal database
     * @return bool
     */
    public function delete() {
        if (!$this->can_delete())
            return false;
        $link = Db::getInstance();
        $query = 'DELETE FROM inventario WHERE item_id = :id';
        $stmt = $link->prepare($query);
        $stmt->bindParam(':id', $this->id);
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

    public function in_feste() {
        $link = Db::getInstance();
        $query = "SELECT COUNT(item_id) FROM oggetti_party WHERE item_id = :id";
        $stmt = $link->prepare($query);
        $stmt->bindParam('id', $this->id);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function in_temi() {
        $link = Db::getInstance();
        $query = "SELECT COUNT(item_id) FROM oggetti_temi WHERE item_id = :id";
        $stmt = $link->prepare($query);
        $stmt->bindParam('id', $this->id);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function can_delete() {
        return $this->in_temi() + $this->in_feste() == 0;
    }

    /**
     * Crea un oggetto
     * @param string $name
     * @param int $number
     * @param int $ward
     * @param int $consumable
     * @return Item|null
     */
    public static function create($name, $number = 1, $ward = 1, $consumable = 0) {
        $link = Db::getInstance();
        $query = 'INSERT INTO inventario (item_name, item_number, item_ward, item_consumable) VALUES (:name, :number, :ward, :consumable)';
        $stmt = $link->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam('number', $number);
        $stmt->bindParam(':ward', $ward);
        $stmt->bindParam(':consumable', $consumable);
        try {
            //$link->beginTransaction();
            $stmt->execute();
            //$link->commit();
            return self::get_item($link->lastInsertId('item_id'));
        } catch (PDOException $e) {
            echo $e->getMessage();
            $link->rollBack();
            return null;
        }
    }
}