<?php
/**
 * Created by PhpStorm.
 * User: Francesco
 * Date: 28/03/2017
 * Time: 16:04
 */


class MainView
{
    private static $scripts;
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

    public static function getCustomJs() {
        global $action;
        if(file_exists(ABS_PATH.'/assets/scripts/'.$action.'.js'))
            //echo $action;
            echo '<script type="text/javascript" src="assets/scripts/'.$action.'.js"></script>';
    }

    public static function push_script($script) {
        if(!isset(self::$scripts))
            self::$scripts = [];
        array_push(self::$scripts, $script);
    }

    public static function get_scripts() {
        if (isset(self::$scripts) && array_count_values(self::$scripts) > 0) {
            $result = '';
            foreach (self::$scripts as $script) {
                $result .= $script;
            }
            $result.='';
            echo $result;
        }
    }

    public static function page_with_menu() {
        global $action;
        if(User::getCurrent()->access_level == 0 and $action == 'login')
            return false;
        else
            return true;
    }
}