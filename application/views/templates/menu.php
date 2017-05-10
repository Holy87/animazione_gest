<?php
require_once ABS_PATH . '/application/models/MenuElement.php';
require_once ABS_PATH . '/application/models/MenuContainer.php';

function draw_menu_elements() {

    $html = '';
    global $action;
    $menu_elements = new MenuContainer();
    /** @var MenuElement $menu_element */
    foreach($menu_elements->get_elements() as $menu_element)
    {
        if ($action == $menu_element->get_tag())
            $active = ' active';
        else
            $active = '';
        $html.=
        '<li class="nav-item'.$active.'"><a class="nav-link" href="'.$menu_element->get_url().'">'.$menu_element->get_text().'</a></li>';
    }
    echo $html;
}

function draw_right_elements() {
    global $user;
    if($user->access_level > 0) {
        draw_logged_elements();
    } else {
        draw_login_elements();
    }
}

function draw_logged_elements() {
    global $user;
    $code = 'Benvenuto, '.$user->name;
    $code.= '  <li><a href="#"><i class="fa fa-calendar" aria-hidden="true"></i>Eventi</a></li>';
    if ($user->access_level > 1) {
        $code.= '<li><a href="#"><i class="fa fa-film" aria-hidden="true"></i>&nbsp; Temi Feste</a></li>
        <li><a href="#"><i class="fa fa-archive" aria-hidden="true"></i>&nbsp; Inventario</a></li>';
    }
    if ($user->can_create_users()) {
        $code.= '<li><a href="#"><i class="fa fa-address-book" aria-hidden="true"></i>&nbsp; Gestione utenti</a></li>';
    }
    $code.='<li><a href="#"><i class="fa fa-sign-out" aria-hidden="true"></i>&nbsp; Esci</a></li>';
    echo $code;
}

function draw_login_elements() {
    
}
?> 




<div class="collapse navbar-collapse" id="navbarCollapse">
    <!-- LEFT MENU COMMANDS -->
    <ul class="navbar-nav mr-auto">
        <?php draw_menu_elements(); ?>
    </ul>

    <!-- RIGHT MENU COMMANDS -->
    <ul class="nav navbar-nav navbar-right">
        <?php draw_right_elements(); ?>
    </ul>
</div>