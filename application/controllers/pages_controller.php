<?php
class PagesController {
    public function home() {
        require_once 'application/views/pages/home.php';
    }

    public function error() {
        require_once 'application/views/pages/error.php';
    }
}