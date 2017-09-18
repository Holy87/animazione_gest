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
    //<img class="rounded-circle img-fluid low-profile" src="'.$user->get_avatar_url().'">
    if($user->access_level > 0)
        $code .= logged_options();
    echo $code;
}

function logged_options() {
    global $user;
    /** @noinspection HtmlUnknownTarget */
    return '
<div class="avatar-little">
    <div class="circle-avatar" style="background-image:url('.$user->get_avatar_url().'"></div>
</div>
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="profile" id="dropdown02" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> '.$user->friendly_name.'</a>
    <div class="dropdown-menu" aria-labelledby="dropdown02">
        <a class="dropdown-item" href="profile">Profilo</a>
        <a class="dropdown-item" href="changelog">Novità</a>
        <a class="dropdown-item" href="logout">Esci</a>
    </div>
</li>';
}

function login_button() {
    /** @noinspection HtmlUnknownTarget */
    return '<a class="btn btn-success my-2 my-sm-0" href="login">Entra</a>';
}

function draw_login_elements() {
    echo login_button();
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