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
    public $party_id;
    public $theme_id;
    public $customer;
    public $address;

    public function __construct($id, $theme, $date, $customer, $address, $creator) {
        $this->party_id = $id;
        $this->theme_id = $theme;
        $this->date = $date;
        $this->customer = $customer;
        $this->address = $address;
        $this->creator = $creator
    }

    public function get_animators() {

    }

    public function get_material() {

    }

    public function is_done() {

    }

    /**
     * @param null $start
     * @return array
     */
    public static function get_all($start = null) {
        $link = Db::getInstance();
        $query = 'SELECT * FROM feste';
        $list = [];
        $stmt = $link->prepare($query);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($rows as $row) {
            $elem = new Party($row['party_id'], $row['tema'], $row['data'], $row['cliente'], $row['indirizzo'], $row['creatore']);
            array_push($list, $elem);
        }
        return $list;
    }

    /**
     * @param int $party_id
     * @return Party
     */
    public static function get_party($party_id) {
        $link = Db::getInstance();
        $query = 'SELECT * FROM feste where party_id = :id';
        $stmt = $link->prepare($query);
        $stmt->bindParam(':id', $party_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return new Party($row['party_id'], $row['tema'], $row['data'], $row['cliente'], $row['indirizzo'], $row['creatore']);
    }
}