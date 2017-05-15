<?php
class PagesController {
    public function home() {
        require_once ABS_PATH.'/application/views/pages/home.php';
    }

    public function error() {
        require_once ABS_PATH.'/application/views/pages/error.php';
    }

    public function login() {
        require_once ABS_PATH.'/application/views/pages/login.php';
    }

    public function events() {
        require_once ABS_PATH.'application/views/pages/events.php';
    }

    public function register() {

    }
}