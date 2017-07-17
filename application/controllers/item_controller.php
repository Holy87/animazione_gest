<?php

/**
 * Created by PhpStorm.
 * User: Gold Service
 * Date: 16/05/2017
 * Time: 07:23
 */
require_once ABS_PATH.'/application/models/Item.php';
require_once ABS_PATH.'/application/models/User.php';

class ItemController
{
    private function __construct() {}
    private function __clone() {}

    /**
     * Restituisce tutti gli oggetti come codifica json
     */
    public static function get_all_items() {
        $items = [];
        $link = Db::getInstance();
        $query = 'SELECT item_id, item_name, item_number, item_ward, item_consumable FROM inventario ORDER BY item_name';
        $stmt = $link->prepare($query);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($rows as $row)
        {
            $items[] = ['id' => $row['item_id'], 'name' => $row['item_name'], 'number' => $row['item_number'], 'e_id' => $row['item_id'], 'ward' => $row['item_ward'], 'consumable' => $row['item_consumable']];
        }
        echo json_encode(["data" =>$items]);
    }

    public static function save_item() {
        if(!User::getCurrent()->can_edit_events())
            return json_encode(['ok' => false, 'reason' => 'Permessi insufficienti']);
        if(isset($_POST['item-id'])) {
            if(isset($_POST['consumable']))
                $consumable = 1;
            else
                $consumable = 0;
            $item = Item::get_item($_POST['item-id']);
            if ($item != null) {
                $item->number = $_POST['number'];
                $item->name = $_POST['name'];
                $item->consumable = $consumable;
                $item->ward = $_POST['ward'];
                $item->floor = $_POST['floor'];
                $item->save();
                return json_encode(['ok' => true, 'id' => $item->id, 'name' => $item->name, 'number' => $item->number, 'ward' => $item->ward, 'consumable' => $consumable]);
            } else {
                return json_encode(['ok' => false]);
            }
        } else {
            return json_encode(['ok' => false]);
        }
    }

    public static function delete_item() {
        if(!User::getCurrent()->can_edit_events())
            return json_encode(['ok' => false, 'reason' => 'Permessi insufficienti']);
        $item_object = Item::get_item($_POST['item-id']);
        if ($item_object == null) {
            return json_encode(['ok' => false, 'reason' => 'Oggetto non trovato', 'code' => -1]);
        } else {
            return json_encode($item_object->delete());
        }
    }

    public static function force_delete_item() {
        if(!User::getCurrent()->can_edit_events())
            return json_encode(['ok' => false, 'reason' => 'Permessi insufficienti']);
        $item_object = Item::get_item($_POST['item-id']);
        if ($item_object == null) {
            return json_encode(['ok' => false, 'reason' => 'Oggetto non trovato', 'code' => -1]);
        } else {
            return json_encode($item_object->force_delete());
        }
    }

    public static function create_item() {
        if(!User::getCurrent()->can_edit_events())
            return json_encode(['ok' => false, 'reason' => 'Permessi insufficienti']);
        if(isset($_POST['consumable']))
            $consumable = 1;
        else
            $consumable = 0;
        $item = Item::create($_POST['name'], $_POST['number'], $_POST['ward'], $consumable, $_POST['floor']);
        if ($item != null) {
            return json_encode(['ok' => true, 'id' => $item->id, 'name' => $item->name, 'number' => $item->number]);
        } else {
            return json_encode(['ok' => false]);
        }
    }

    public static function get_item() {
        $item = Item::get_item($_GET['id']);
        echo json_encode(['id' => $item->id, 'name' => $item->name, 'number' => $item->number, 'consumable' => $item->consumable, 'ward' => $item->ward, 'floor' => $item->floor]);
    }
}