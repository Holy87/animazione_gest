<?php

/**
 * Created by PhpStorm.
 * User: Gold Service
 * Date: 23/05/2017
 * Time: 10:40
 */
require_once ABS_PATH.'/application/models/PartyTheme.php';

class ThemeController
{
    public static function get_themes() {
        $themes = PartyTheme::getAllThemes();
        $data = [];
        /** @var PartyTheme $theme */
        foreach($themes as $theme) {
            $data[] = ["name" => $theme->name, "price" => $theme->price, "items" => $theme->item_names(), "e_id" => $theme->id, "description" => $theme->description];
        }
        return json_encode(["data" => $data]);
    }

    public static function get_theme_price() {
        if(User::getCurrent()->access_level > 0) {
            $theme = PartyTheme::getTheme($_GET['theme']);
            if($theme != null) {
                return json_encode(['ok' => true, 'value' => $theme->price]);
            } else
                return json_encode(['ok' => false, 'reason' => 'Tema non trovato', 'code' => 0]);
        } else
            return json_encode(['ok' => false, 'reason' => 'Non hai i permessi per accedere alla risorsa', 'code' => -1]);
    }

    public static function create_theme() {
        if(User::getCurrent()->can_edit_events()) {
            $name = $_POST['theme-name'];
            $price = $_POST['theme-price'];
            $description = $_POST['theme-description'];
            return json_encode(PartyTheme::create($name, $description, $price));
        } else
            return json_encode(['ok' => false, 'reason' => 'Non hai i permessi per creare e modificare temi.']);
    }

    public static function edit_theme() {
        if(isset($_POST['mode']) && $_POST['mode'] == 'create')
            return self::create_theme();
        if(User::getCurrent()->can_edit_events()) {
            //return json_encode(['ok' => false, 'reason' => $_POST['theme-name']]);
            $name = $_POST['theme-name'];
            $description = $_POST['theme-description'];
            $price = $_POST['theme-price'];
            $id = $_POST['theme-id'];
            $theme = PartyTheme::getTheme($id);
            if($theme == null)
                return json_encode(['ok' => false, 'reason' => 'Tema festa non trovato.', 'code' => 0]);
            $theme->name = $name;
            $theme->description = $description;
            $theme->price = $price;
            return json_encode($theme->save());
        } else {
            return json_encode(['ok' => false, 'reason' => 'Non hai i permessi per creare e modificare temi.', 'code' => -1]);
        }
    }

    public static function delete_theme() {
        if(User::getCurrent()->access_level < 1)
            return json_encode(['ok' => false, 'reason' => 'Non hai permessi sufficienti.', 'code' => -1]);
        if(isset($_POST['theme-id'])) {
            $theme = PartyTheme::getTheme($_POST['theme-id']);
            if($theme == null) {
                return json_encode(['ok' => false, 'reason' => 'Non esiste nessun tema per id '.$_POST['theme-id'], 'code' => 0]);
            } else
                return json_encode($theme->delete());//
        } else {
            return json_encode(['ok' => false, 'reason' => 'Parametri errati', 'code' => -2]);
        }
    }

    public static function get_theme_items() {
        if(User::getCurrent()->access_level > 0) {
            $theme = PartyTheme::getTheme($_GET['theme_id']);
            if($theme == null)
                return json_encode(['ok' => false, 'reason' => 'Il tema cercato non esiste.', 'code' => 0]);
            $items = $theme->get_items();
            /** @var Item $item */
            $rest = [];
            foreach($items as $item) {
                $rest[] = ['name' => $item->name, 'number' => $theme->get_item_number($item->id), 'id' => $item->id];
            }
            return json_encode(['data' => $rest]);
        } else
            return json_encode(['ok' => false, 'reason' => 'Non hai permessi sufficienti.', 'code' => -1]);
    }

    public static function increase_item_number() {
        if(User::getCurrent()->access_level <= 0)
            return json_encode(['ok' => false, 'reason' => 'Non hai permessi sufficienti.', 'code' => -1]);
        if(isset($_POST['theme_id']) && isset($_POST['item_id'])) {
            $link = Db::getInstance();
            $query = "UPDATE oggetti_temi SET item_number = item_number + 1 WHERE item_id = :iid AND theme_id = :tid";
            $stmt = $link->prepare($query);
            $stmt->bindParam(':iid', $_POST['item_id']);
            $stmt->bindParam(':tid', $_POST['theme_id']);
            if($stmt->execute())
                return json_encode(['ok' => true]);
            else
                return json_encode(['ok' => false, 'reason' => $stmt->errorInfo(), 'code' => $stmt->errorCode()]);

        } else
            return json_encode(['ok' => false, 'reason' => 'Parametri errati', 'code' => -2]);
    }

    public static function decrease_item_number() {
        if(User::getCurrent()->access_level <= 0)
            return json_encode(['ok' => false, 'reason' => 'Non hai permessi sufficienti.', 'code' => -1]);
        if(isset($_POST['theme_id']) && isset($_POST['item_id'])) {
            $link = Db::getInstance();
            $query = "UPDATE oggetti_temi SET item_number = item_number - 1 WHERE item_id = :iid AND theme_id = :tid AND item_number > 1";
            $stmt = $link->prepare($query);
            $stmt->bindParam(':iid', $_POST['item_id']);
            $stmt->bindParam(':tid', $_POST['theme_id']);
            if($stmt->execute())
                return json_encode(['ok' => true]);
            else
                return json_encode(['ok' => false, 'reason' => $stmt->errorInfo(), 'code' => $stmt->errorCode()]);

        } else
            return json_encode(['ok' => false, 'reason' => 'Parametri errati', 'code' => -2]);
    }

    public static function add_item() {
        if(User::getCurrent()->access_level <= 0)
            return json_encode(['ok' => false, 'reason' => 'Non hai permessi sufficienti.', 'code' => -1]);
        if(isset($_POST['theme-id']) && isset($_POST['item-id'])) {
            $theme = PartyTheme::getTheme($_POST['theme-id']);
            if ($theme == null)
                return json_encode(['ok' => false, 'reason' => 'Il tema cercato non esiste.', 'code' => 0]);
            if($theme->get_item_number($_POST['item-id']) <= 0) {
                $query = "INSERT INTO oggetti_temi (item_id, theme_id, item_number) VALUES (:iid, :tid, :num)";
            } else {
                $query = "UPDATE oggetti_temi SET item_number = item_number + :num WHERE item_id = :iid AND theme_id = :tid";
            }
            $link = Db::getInstance();
            $stmt = $link->prepare($query);
            $theme_id = $_POST['theme-id'];
            $item_id = $_POST['item-id'];
            $number = $_POST['item-number'];
            $stmt->bindParam(':iid', $item_id);
            $stmt->bindParam(':tid', $theme_id);
            $stmt->bindParam(':num', $number);
            if($stmt->execute())
                return json_encode(['ok' => true]);
            else
                return json_encode(['ok' => false, 'reason' => $stmt->errorInfo(), 'code' => $stmt->errorCode()]);
        } else {
            $message = 'Parametri errati: ';
            if(!isset($_POST['theme-id']))
                $message.= 'theme-id ';
            if(!isset($_POST['item-id']))
                $message.= 'item-id';
            return json_encode(['ok' => false, 'reason' => $message, 'code' => -2]);
        }
    }

    public static function remove_item() {
        if(User::getCurrent()->access_level > 1) {
            if(isset($_POST['theme-id']) && isset($_POST['item-id']))
            {
                $theme = PartyTheme::getTheme($_POST['theme-id']);
                if($theme == null)
                    return json_encode(['ok' => false, 'reason' => 'Il tema cercato non esiste.', 'code' => 0]);
                return json_encode($theme->delete_item_from_id($_POST['item-id']));
            } else
                return json_encode(['ok' => false, 'reason' => 'Parametri richiesta errati.', 'code' => -2]);
        } else
            return json_encode(['ok' => false, 'reason' => 'Non hai permessi sufficienti.', 'code' => -1]);
    }
}