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

    public function __construct($id, $name, $price)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
    }

    public function save() {
        $link = Db::getInstance();
        $query = 'UPDATE temi SET theme_name = :name, theme_price = :price
                  WHERE theme_id = :id';
        $stmt = $link->prepare($query);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
    }

    public function delete() {
        $link = Db::getInstance();
        $query = "DELETE from temi WHERE theme_id = :id";
        $stmt = $link->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
    }

    /**
     * @return array
     */
    public static function getAllThemes() {
        $link = Db::getInstance();
        $query = 'SELECT * FROM temi';
        $list = [];
        $stmt = $link->prepare($query);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($rows as $row) {
            $elem = new PartyTheme($row['theme_id'], $row['theme_name'], $row['theme_price']);
            array_push($list, $elem);
        }
        return $list;
    }

    /**
     * @param integer $id
     * @return PartyTheme
     */
    public static function getTheme($id) {
        $link = Db::getInstance();
        $query = 'SELECT * FROM temi WHERE theme_id = :id';
        $stmt = $link->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $theme = $stmt->fetch(PDO::FETCH_ASSOC);
        return new PartyTheme($theme['theme_id'], $theme['theme_name'], $theme['theme_price']);
    }
}