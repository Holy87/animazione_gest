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
            echo '<link rel="stylesheet" href="assets/scripts/'.$action.'.js">';
    }

    public static function push_script($script) {
        if(!isset(self::$scripts))
            self::$scripts = [];
        array_push(self::$scripts, $script);
    }

    public static function get_scripts() {
        if (array_count_values(self::$scripts) > 0) {
            $result = '';
            foreach (self::$scripts as $script) {
                $result .= $script;
            }
            $result.='';
            echo $result;
        }
    }
}