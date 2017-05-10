<?php
class MenuElement
{
    private $text;
    private $tag;
    private $access;
    private $url;

    public function __construct($text, $tag, $access, $url) {
        $this->text = $text;
        $this->tag = $tag;
        $this->access = $access;
        $this->url = $url;
    }

    public function get_text() { return $this->text;}

    public function get_tag() {return $this->tag;}

    public function get_url() {
        //TODO: implementare
    }

    public function get_access() {return 1;}
}