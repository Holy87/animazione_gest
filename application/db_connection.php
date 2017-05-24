<?php
/**
 * Created by PhpStorm.
 * User: Francesco
 * Date: 22/03/2017
 * Time: 23:41
 */
class Db {
    // Singleton class
    private static $instance = NULL;
    private function __construct() {}
    private function __clone() {}
    // Get the connection
    public static function getInstance() {
        if (!isset(self::$instance)) {
            $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
            try {
                self::$instance = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS, $pdo_options);
            } catch(PDOException $exception) {
                if($exception->getCode() == 1049)
                {
                    self::init();
                }
            }
        }
        return self::$instance;
    }

    public static function init() {
        self::$instance = new PDO("mysql:host=".DB_HOST, DB_USER, DB_PASS);
        self::$instance->query("CREATE DATABASE `".DB_NAME."`");
        self::$instance->query("use `".DB_NAME."`");
        self::$instance->query(self::creation_string());
    }

    public static function creation_string() {
        $filename = ABS_PATH."application/db_init.sql";
        $myfile = fopen($filename, "r") or die("Impossibile aprire il file $filename");
        $query = fread($myfile,filesize($filename));
        fclose($myfile);
        return $query;
    }
}