<?php
class Carousel {
    //private static $instance = NULL;
    private function __construct() {}
    private function __clone() {}

    /**
     * @return array
     */
    public static function getElements() {
        $link = Db::getInstance();
        $list = [];
        $query = "SELECT * FROM carousels";
        $stmt = $link->prepare($query);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $first = true;
        foreach($rows as $row) {
            $elem = new CarouselElement($row['car_title'], $row['car_text'],
                $row['car_link'], $row['car_image'], $first, $row['car_button']);
            array_push($list, $elem);
            $first = false;
        }
        return $list;
    }
}

