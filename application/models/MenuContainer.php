<?php
class MenuContainer
{
    private $menu_elements;

    /**
     * Creazione delle voci del menu.
     */
    public function __construct()
    {
        $this->menu_elements = [
            new MenuElement('Home', 'home', 1, 'home', 'fa-home'),
            new MenuElement('Eventi', 'events', 1, 'events', 'fa-calendar'),
            new MenuElement('Temi Feste', 'themes', 1, 'themes', 'fa-film'),
            new MenuElement('Inventario', 'items', 1,'items', 'fa-archive'),
            new MenuElement('Utenti', 'users', 3, 'users', 'fa-users')
        ];
    }

    /**
     * @return array
     */
    public function get_elements() {
        $new_ary = [];
        global $user;
        foreach($this->menu_elements as $element) {
            if ($element->get_access() <= $user->access_level)
            {
                array_push($new_ary, $element);
            }
        }
        return $new_ary;
    }

    /**
     * @param MenuElement $element
     */
    public function add_element($element) {
        if($this->menu_elements == null)
        {
            $this->menu_elements = [];
        }
        array_push($this->menu_elements, $element);
    }
}