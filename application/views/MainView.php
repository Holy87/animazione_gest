<?php
/**
 * Created by PhpStorm.
 * User: Francesco
 * Date: 28/03/2017
 * Time: 16:04
 */


class MainView
{
    //private static $instance = NULL;
    private function __construct() {}
    private function __clone() {}

    public static function page_title() {
        if(isset($action)) {
            echo APP_NAME.' | '.$action;
        } else {
            echo APP_NAME;
        }
    }

    public static function getCustomCss() {
        global $action;
        if(file_exists(ABS_PATH.'/assets/styles/'.$action.'.css'))
            echo '<link rel="stylesheet" href="assets/styles/'.$action.'.css">';
    }
}