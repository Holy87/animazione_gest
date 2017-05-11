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

    /**
     * Item constructor.
     * @param $id
     * @param $name
     * @param $number
     */
    public function __construct($id, $name, $number)
    {
        $this->name = $name;
        $this->id = $id;
        $this->number = $number;
    }

    /**
     * Salva l'oggetto
     * @return bool
     */
    public function save() {
        $link = Db::getInstance();
        $query = 'UPDATE inventario SET item_name = :name, item_number = :num
            WHERE item_id = :id';
        $stmt = $link->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':num', $this->number);
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
        return new Item($row['item_id'], $row['item_name'], $row['item_number']);
    }

    /**
     * Cancella l'oggetto dal database
     * @return bool
     */
    public function delete() {
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

    /**
     * Crea un oggetto
     * @param $name
     * @param int $number
     * @return Item|null
     */
    public static function create($name, $number = 1) {
        $link = Db::getInstance();
        $query = 'INSERT INTO inventario (item_name, item_number) VALUES (:name, :number)';
        $stmt = $link->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam('number', $number);
        try {
            $link->beginTransaction();
            $stmt->execute();
            $link->commit();
            return self::get_item($link->lastInsertId());
        } catch (PDOException $e) {
            echo $e->getMessage();
            $link->rollBack();
            return null;
        }
    }
}