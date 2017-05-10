<?php

/**
 * Created by PhpStorm.
 * User: Gold Service
 * Date: 28/03/2017
 * Time: 12:12
 */
class CarouselElement
{
    private $image_url;
    private $url;
    private $title;
    private $description;
    public $active;
    private $button;

    public function  getButton()
    {
        return $this->button;
    }

    /**
     * @return string
     */
    public function getImageUrl()
    {
        return $this->image_url;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * CarouselElement constructor.
     * @param string $title
     * @param string $text
     * @param string $link
     * @param string $image
     * @param boolean $active
     * @param string $button
     */
    public function __construct($title, $text, $link, $image, $active, $button) {
        $this->title = $title;
        $this->description = $text;
        $this->image_url = $image;
        $this->url = $link;
        $this->active = $active;
        $this->button = $button;
    }

    /**
     * @return string
     */
    public function get_class() {
        if ($this->active)
            return ' active';
        else
            return '';
    }
}