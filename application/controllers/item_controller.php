<?php

/**
 * Created by PhpStorm.
 * User: Gold Service
 * Date: 16/05/2017
 * Time: 07:23
 */
require_once ABS_PATH.'/application/models/Item.php';

class ItemController
{
    private function __construct() {}
    private function __clone() {}

    /**
     * Restituisce tutti gli oggetti come codifica json
     * @return string
     */
    public static function get_all_items() {
        $items = [];
        $link = Db::getInstance();
        $query = 'SELECT item_id, item_name, item_number FROM inventario ORDER BY item_name';
        $stmt = $link->prepare($query);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($rows as $row)
        {
            $items[] = ['id' => $row['item_id'], 'name' => $row['item_name'], 'number' => $row['item_number']];
        }
        echo json_encode($items);
    }

    public static function save_item() {
        if(isset($_POST['item-id'])) {
            $item = Item::get_item($_POST['item-id']);
            if ($item != null) {
                $item->number = $_POST['number'];
                $item->name = $_POST['name'];
                $item->save();
                echo json_encode(['ok' => true, 'id' => $item->id, 'name' => $item->name, 'number' => $item->number]);
            } else {
                echo json_encode(['ok' => false]);
            }
        } else {
            echo json_encode(['ok' => false]);
        }

    }

    public static function delete_item() {
        $item_object = Item::get_item($_POST['item_id']);
        if ($item_object == null) {
            echo json_encode(['ok' => false]);
        } else {
            $item_object->delete();
            echo json_encode(['ok' => true]);
        }
    }

    public static function create_item() {
        $item = Item::create($_POST['name'], $_POST['number']);
        if ($item != null) {
            echo json_encode(['ok' => true, 'id' => $item->id, 'name' => $item->name, 'number' => $item->number]);
        } else {
            echo json_encode(['ok' => false]);
        }
    }

    public static function get_item() {
        $item = Item::get_item($_GET['id']);
        echo json_encode(['id' => $item->id, 'name' => $item->name, 'number' => $item->number]);
    }
}