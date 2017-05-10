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

    public function __construct($id, $name, $number)
    {
        $this->name = $name;
        $this->id = $id;
        $this->number = $number;
    }

    public function save() {
        $link = Db::getInstance();
        $query = 'UPDATE inventario SET item_name = :name, item_number = :num
            WHERE item_id = :id';
        $stmt = $link->prepare($squery);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':num', $this->number);
        $stmt->execute();
    }

    public function delete() {
        $link = Db::getInstance();
        $query = 'DELETE FROM inventario WHERE item_id = :id';
        $stmt = $link->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
    }

    public static function create($name, $number = 1) {
        $link = Db::getInstance();
        $query = 'INSERT INTO inventario (item_name, item_number) VALUES (:name, :number)';
        $stmt = $link->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam('number', $number);
        $stmt->execute();
    }
}