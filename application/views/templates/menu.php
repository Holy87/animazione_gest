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
    $code = '';
    /** @noinspection HtmlUnknownTarget */
    $code .= '<li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="profile" id="dropdown02" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img class="rounded-circle img-fluid low-profile" src="'.$user->get_avatar_url().'"> '.$user->friendly_name.'</a>
              <div class="dropdown-menu" aria-labelledby="dropdown02">
                <a class="dropdown-item" href="profile">Profilo</a>
                <a class="dropdown-item" href="logout">Esci</a>
              </div>
            </li>';
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