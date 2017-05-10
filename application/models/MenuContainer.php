<?php
class MenuContainer
{
    private $menu_elements;

    /**
     * MenuContainer constructor.
     */
    public function __construct()
    {
        $this->menu_elements = [
            new MenuElement('Home', 'home', '0', 'home'),
            new MenuElement('Miei giochi', 'games', 1, 'games')
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