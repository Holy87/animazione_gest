<?php
class MenuElement
{
    private $text;
    private $tag;
    private $access;
    private $url;
    private $icon;

    public function __construct($text, $tag, $access, $url, $icon = null) {
        $this->text = $text;
        $this->tag = $tag;
        $this->access = $access;
        $this->url = $url;
        $this->icon = $icon;
    }

    public function get_text() {
        $icon = $this->icon;
        if ($icon != null)
            return "<i class=\"fa $icon\" aria-hidden=\"true\"></i> ".$this->text;
        else
            return $this->text;
    }

    public function get_tag() {return $this->tag;}

    public function get_url() {
        //TODO: implementare
    }

    public function get_access() {return 1;}
}