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

    public static function create_theme() {
        if(User::getCurrent()->can_edit_events()) {
            $name = $_POST['theme-name'];
            $price = $_POST['theme-price'];
            $description = $_POST['theme-description'];
            return PartyTheme::create($name, $description, $price);
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
}