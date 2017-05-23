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
        if(User::getCurrent()->can_edit_events()) {

        }
    }
}