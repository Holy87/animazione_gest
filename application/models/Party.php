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

    public function __construct($id, $theme, $date) {
        $this->party_id = $id;
        $this->theme_id = $theme;
        $this->date = $date;
    }

    public function get_animators() {

    }

    public function get_material() {

    }

    public function is_done() {

    }

    public static function get_all($start = null) {

    }

    public static function get_party($party_id) {

    }
}