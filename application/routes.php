<?php
/**
 * Created by PhpStorm.
 * User: Francesco
 * Date: 22/03/2017
 * Time: 23:22
 * Thanks to http://requiremind.com/a-most-simple-php-mvc-beginners-tutorial/
 */

/**
 * Calls the proper controller and executing the selected action
 * @param string $controller
 * @param string $action
 **/
function call($controller = 'pages', $action = 'home') {
    // require the file that matches the controller name
    /** @noinspection PhpIncludeInspection */
    $path = ABS_PATH.'/application/controllers/' . $controller . '_controller.php';
    if (file_exists($path)) {
        require_once($path);
        $controller = new NavigationController();
        $controller->go_to($action);
    } else {
        call('pages', 'error');
    }
}

/**
 * @param User $user
 */
function load_page($user) {
    global $controller;
    global $action;
    if(isset($controller) and isset($action)) {
        if(permit_access($action, $user))
            call($controller, $action);
        else
            call('pages', 'error');
    } else {
        if($user->access_level > 0)
            call('pages', 'error');
        else
            call('pages', 'login');
    }
}

/**
 * @param string $action
 * @param User $user
 * @return bool
 */
function permit_access($action, $user) {
    $access_required = [
        'home' => 1,
        'profile' => 1,
        'events' => 1,
        'account' => 3,
        'items' => 2,
        'theme' => 2,
        'themes' => 2,
        'users' => 2,
        'eventdetails' => 2,
        'eventpage' => 1
    ];
    if(!in_array($action, $access_required))
    {
        return true;
    }
    return $user->access_level >= $access_required[$action];
}